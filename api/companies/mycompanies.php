<?php
require_once "../../Database/db.php";
require_once "../../Model/Company.php";

use Database\Db;
session_start();

$dbInstance = new Db();
$db = $dbInstance->connect();

$company = new Company();

if ($_POST["action"] == "saveMyCompany") {
    $id = $_POST["id"];

    $data = [
        "id" => $id,
        "user_id" => $_SESSION["user"]->id,
        "firm_name" => $_POST["firm_name"],
        "phone" => $_POST["phone"],
        "email" => $_POST["email"],
        "description" => $_POST["description"],
    ];

    $brand_logo = $_FILES["brand_logo"];
    $file_path = $brand_logo["tmp_name"];
    $path = "../../uploads/";
    $file_name =  uniqid() . $brand_logo["name"];

    if (move_uploaded_file($file_path, $path . $file_name)) {
        //Onceki yüklenen dosyayı bul 
        $old_brand_logo = $company->findMyFirmLogoName($id);

        if ($old_brand_logo) {
            // Dosya yolunu oluştur
            $old_brand_logo_file = $path . $old_brand_logo->brand_logo;
            // Eğer dosya varsa ve bir dosya ise
            if (is_file($old_brand_logo_file)) {
                // Dosyayı silmeyi dene
                if (!unlink($old_brand_logo_file)) {
                    // Hata yönetimi: Dosya silinemedi
                    error_log("Dosya silinemedi: $old_brand_logo_file");
                }
            }
        }

        $data["brand_logo"] =  $file_name;
    }

    try {
        $lastInsertId = $company->saveMyFirms($data);
        $status = "success";
        if ($id == 0) {
            $message = "Firma başarıyla kaydedildi.";
        } else {
            $message = "Firma başarıyla güncellendi.";
        }
    } catch (PDOException $e) {
        $status = "error";
        $message =  $e->getMessage();
    }

    $res = [
        "status" => $status,
        "message" => $message,
    ];

    echo json_encode($res);
}

if ($_POST["action"] == "deleteMyCompany") {
    $id = $_POST["id"];

    $old_brand_logo = $company->findMyFirmLogoName($id);

    if ($old_brand_logo) {
        $path = "../../uploads/";
        $old_brand_logo_file = $path . $old_brand_logo->brand_logo;
        if (is_file($old_brand_logo_file)) {
            if (!unlink($old_brand_logo_file)) {
                error_log("Dosya silinemedi: $old_brand_logo_file");
            }
        }
    }

    try {
         $company->deleteMyFirm($id);
        $status = "success";
        $message = "Firma başarıyla silindi.!!!";
    } catch (PDOException $e) {
        $status = "error";
        $message =  $e->getMessage();
    }

    $res = [
        "status" => $status,
        "message" => $message,
    ];

    echo json_encode($res);
}
