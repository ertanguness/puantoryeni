<?php
define("ROOT", $_SERVER['DOCUMENT_ROOT']);
require_once "../../Database/require.php";
require_once "../../Model/Persons.php";
require_once ROOT . "/Model/Bordro.php";
require_once ROOT . "/App/Helper/security.php";
require_once ROOT . "/Model/Puantaj.php";
require_once "../../Model/Auths.php";
require_once "../../App/Helper/helper.php";

use App\Helper\Security;
use Random\Engine\Secure;
use App\Helper\Helper;

$Puantaj = new Puantaj();
$Bordro = new Bordro();
$Persons = new Persons();
$Auths = new Auths();


if ($_POST["action"] == "savePerson") {
    $id = Security::decrypt($_POST["id"]);
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
        $lastInsertId = $Persons->saveWithAttr($data) ?? $_POST["id"];
        $status = "success";
        if ($id == 0) {
            $message = "Personel başarıyla kaydedildi.";
        } else {
            $message = "Personel başarıyla güncellendi.";
        }
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
    $id = ($_POST["id"]);
    $person = $Persons->find(Security::decrypt($id));

    //İşlem yapan kullanıcı ile personelin firm id'si aynı olmalı
    $Auths->checkFirmReturn();
    try {
        $Persons->delete($id);
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
