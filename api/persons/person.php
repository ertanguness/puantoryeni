
<?php
define("ROOT", $_SERVER['DOCUMENT_ROOT']);
require_once "../../Database/db.php";
require_once "../../Model/Persons.php";
require_once  ROOT . "/Model/Bordro.php";

use Database\Db;

$Bordro = new Bordro();

$dbInstance = new Db(); // Db sınıfının bir örneğini oluşturuyoruz.
$db = $dbInstance->connect(); // Veritabanı bağlantısını alıyoruz.

$person = new Persons();


if ($_POST["action"] == "savePerson") {
    $id = $_POST["id"];
    if($id > 0 ){
        //personelin göreve başlama tarihinden önceki tüm maaşları sil
        $Bordro->deleteAllSalaries($id, $_POST["job_start_date"]);
    }
    $lastInsertId = 0;
    $data = [
        "id" => $id,
        "full_name" => $_POST["full_name"],
        "kimlik_no" => $_POST["kimlik_no"],
        "email" => $_POST["email"],
        "phone" => $_POST["phone"],
        "address" => $_POST["address"],
        "job" => $_POST["job"],
        "job_group" => $_POST["job_groups"],
        "firm_id" => $_POST["firm_id"],
        "wage_type" => $_POST["wage_type"],
        "iban_number" => $_POST["iban_number"],
        // "salary" => $_POST["salary"],
        "daily_wages" => $_POST["daily_wages"],
        "job_start_date" => $_POST["job_start_date"],
        "job_end_date" => $_POST["job_end_date"],
        // "status" => $_POST["status"],
    ];

    try {
        $lastInsertId = $person->saveWithAttr($data) ?? $id;
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
                $message =  $e->getMessage();
            }
        } else {
            $message =  $e->getMessage();
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
    $id = $_POST["id"];
    try {
        $person->delete($id);
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
