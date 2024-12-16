<?php
define("ROOT", $_SERVER['DOCUMENT_ROOT']);
require_once "../../Database/require.php";
require_once "../../Model/Persons.php";
require_once ROOT . "/Model/Bordro.php";
require_once ROOT . "/App/Helper/security.php";
require_once ROOT . "/Model/Puantaj.php";
require_once "../../Model/Auths.php";
require_once "../../App/Helper/helper.php";
require_once "../../Model/Projects.php";


use App\Helper\Security;
use Random\Engine\Secure;
use App\Helper\Helper;

$Puantaj = new Puantaj();
$Bordro = new Bordro();
$Persons = new Persons();
$Auths = new Auths();
$Projects = new Projects();


if ($_POST["action"] == "savePerson") {
    //personel kaydetme yetkisi var mı kontrol et
    $Auths->hasPermissionReturn('personnel_add_update');

    //gelen id 0 dan farklı ise şifreyi çöz, değilse 0 ata
    $id = $_POST["id"] != 0 ? Security::decrypt($_POST["id"]) : 0;

    if ($id > 0) {
        //personelin göreve başlama tarihinden önceki tüm maaşları sil
        $Bordro->deleteAllSalaries($id, $_POST["job_start_date"]);

        //personelin göreve başlama tarihinden önceki ve işten ayrılma tarihinden sonraki tüm puantajları sil
        $Puantaj->deletePastAttendanceRecords($id, $_POST["job_start_date"], $_POST["job_end_date"]);
    }
    $lastInsertId = 0;
    $data = [
        "id" => $id,
        "full_name" => $_POST["full_name"],
        "kimlik_no" => $_POST["kimlik_no"],
        "email" => filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) ? $_POST["email"] : null,
        "phone" => $_POST["phone"],
        "address" => Security::escape($_POST["address"]),
        "job" => $_POST["job"],
        "job_group" => $_POST["job_groups"],
        "firm_id" => $_SESSION["firm_id"],
        "wage_type" => $_POST["wage_type"],
        "iban_number" => Security::escape($_POST["iban_number"]),
        // "salary" => $_POST["salary"],
        "daily_wages" => Helper::formattedMoneyToNumber($_POST["daily_wages"]),
        "job_start_date" => $_POST["job_start_date"],
        "job_end_date" => $_POST["job_end_date"],

        // "status" => $_POST["status"],
    ];



    try {
        //Yeni kayıt ise geriye şifreli id döner, güncelleme ise Post ile gelen şifreli id döner
        $lastInsertId = $Persons->saveWithAttr($data) ?? $_POST["id"];
        $status = "success";
        if ($id == 0) {
            $message = "Personel başarıyla kaydedildi.";
        } else {
            $message = "Personel başarıyla güncellendi.";
        }


        //Personelin çalıştığı projeleri kaydet
        $Projects->savePersonProjects(Security::decrypt($lastInsertId), $_POST["person_project"]);

    } catch (PDOException $e) {
        $status = "error";
        if ($e->errorInfo[1] == 1062) {
            // Hata mesajından ihlal edilen benzersiz kısıtın adını çıkar
            preg_match('/Duplicate entry .* for key \'(.*)\'/', $e->getMessage(), $matches);
            $violatedField = $matches[1] ?? 'Bilinmeyen alan';
            if ($violatedField == 'kimlik_no') {
                $message = "Bu kimlik numarası zaten kayıtlı.";
            } elseif ($violatedField == 'phone') {
                $message = "Bu telefon numarası zaten kayıtlı.";
            } else {
                $message = $e->getMessage();
            }
        } else {
            $message = $e->getMessage();
        }
    }
    $res = [
        "status" => $status,
        "message" => $message,
        "lastid" => $lastInsertId
    ];
    echo json_encode($res);
}

if ($_POST["action"] == "deletePerson") {

    //personel silme yetkisi var mı kontrol et
    $Auths->hasPermissionReturn('personnel_delete');

    $id = ($_POST["id"]);
    $person = $Persons->find(Security::decrypt($id));

    //İşlem yapan kullanıcı ile personelin firm id'si aynı olmalı
    $Auths->checkFirmReturn();
    try {

        // burada personelin işlemlerinin de silinmesi için kontrol eklenecek
        $Persons->softDelete($id);
        $status = "success";
        $message = "Personel başarıyla silindi.";
    } catch (PDOException $e) {
        $status = "error";
        $message = $e->getMessage();
    }
    $res = [
        "status" => $status,
        "message" => $message
    ];
    echo json_encode($res);
}




//Gelir gider bilgilerindeki kayıtları silmek için
if ($_POST['action'] == 'deletePayment') {

    //Ödeme Silme Yetkisi var mı kontrol et
    $Auths->hasPermissionReturn("delete_staff_payment");

    $id = $_POST['id'];
    $person_id = Security::decrypt($_GET['person_id']);
    $income_expense = '';
    $status = 'success';
    $message = $id;

    $type = $_GET["type"];

    try {
         $db->beginTransaction();

         //Gelen type değerine göre silme işlemi yapılır
         if ($type === 'Puantaj Çalışma') {
            $Puantaj->delete($id);
        } else {
            $Bordro->delete($id);
        }

        // Personelin, maas_gelir_kesinti tablosundaki ödeme, kesinti ve gelir toplamlarını getirir
        $income_expense = $Bordro->sumAllIncomeExpense($person_id);

        //Bakiye hesaplanır
        $balance = Helper::formattedMoney($income_expense->total_income - $income_expense->total_payment - $income_expense->total_expense);  // Bakiye

        //Toplam Gelir
        $income_expense->total_income = Helper::formattedMoney($income_expense->total_income ?? 0);

        // Toplam ödeme 
        $income_expense->total_payment = Helper::formattedMoney($income_expense->total_payment ?? 0);

        // Toplam gider
        $income_expense->total_expense = Helper::formattedMoney($income_expense->total_expense ?? 0);

        // Bakiye, değişkenine atama yapılır
        $income_expense->balance = $balance;
        
    
        $message = "İşlem başarılı bir şekilde gerçekleşti.";


         $db->commit();
    } catch (PDOException $e) {
        $db->rollBack();
        $status = 'error';
        $message = $e->getMessage();
    }
    $res = [
        'status' => $status,
        'message' =>  $message,
        'income_expense' => $income_expense,
    ];
    echo json_encode($res);
}