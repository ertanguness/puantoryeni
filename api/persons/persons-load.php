<?php
define('ROOT', $_SERVER["DOCUMENT_ROOT"]);

require_once ROOT . '/Model/Persons.php';
require_once ROOT . '/Database/require.php';
require_once ROOT . '/App/Helper/date.php';
require_once ROOT . '/App/Helper/security.php';
require ROOT . '/vendor/autoload.php';


$firm_id = $_SESSION['firm_id'];

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Helper\Date;
use App\Helper\Security;
$Persons = new Persons();

if ($_POST["action"] == "persons-load-from-xls") {

    $file = $_FILES["persons-load-file"];
    $file_name = $file["name"];
    $file_tmp = $file["tmp_name"];
    $file_size = $file["size"];
    $file_error = $file["error"];
    $file_ext = explode(".", $file_name);
    $file_ext = strtolower(end($file_ext));
    $allowed = ["xls", "xlsx"];

    $lastInsertedId = 0;
    if (in_array($file_ext, $allowed)) {
        try {
            //excel dosyasını okuma
            $spreadsheet = IOFactory::load($file_tmp);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $data = [];
            //hata mesajlarını bir değişkene ata ve sonuç olarak döndür
            $error_message = [];
            foreach ($sheetData as $key => $row) {
                if ($key == 1) {
                    continue;
                }
                //$row = array_map(['Security', 'escape'], $row);

                //Kuralları geçen kayıtların eklenmesi sağlanır
                //full_name en az 3 karakter olmalı
                if (strlen($row["A"]) < 3 || strlen($row["A"]) > 50) {
                    $error_message[] = "Satır $key: Ad Soyad 3-50 karakter arasında olmalıdır.";
                }
                //kimlik_no 11 karakter olmalı
                if (strlen($row["B"]) != 11) {
                    $error_message[] = "Satır $key: Kimlik No 11 karakter olmalıdır.";
                }
                //job_start_date tarih formatında olmalı
                if (!Date::isDate($row["C"])) {
                    $error_message[] = "Satır $key: İşe Başlama Tarihi tarih formatında olmalıdır.";
                }
                //iban_number 26 karakter olmalı
                if (strlen($row["D"]) != 26) {
                    $error_message[] = "Satır $key: Iban Numarası 26 karakter olmalıdır.";

                }
                //daily_wages sayısal olmalı
                if (!is_numeric($row["E"])) {
                    $error_message[] = "Satır $key: Günlük/Aylık Ücret sayısal olmalıdır.";
                }

                // Eğer hata mesajı varsa, bu satırı atla
                if (!empty($error_message)) {
                    continue;
                }


                $data = [
                    "id" => 0,
                    "full_name" => Security::escape($row["A"]),
                    "kimlik_no" => Security::escape($row["B"]),
                    "job_start_date" => Date::dmY($row["C"], "d.m.Y"),
                    "iban_number" => Security::escape($row["D"]),
                    "daily_wages" => Security::escape($row["E"]),
                    "phone" => Security::escape($row["F"]),
                    "email" => Security::escape($row["G"]),
                    "wage_type" => Security::escape($row["H"]),
                    "address" => Security::escape($row["I"]),
                    "description" => Security::escape($row["J"]),
                    "firm_id" => $firm_id,

                ];
                $lastInsertedId = $Persons->saveWithAttr($data) ?? 0;
            }
            //en az bir satır eklendiyse başarılı mesajı ver
            if ($lastInsertedId > 0) {
                $status = "success";
                $message = "Personeller başarıyla yüklendi";
            } else {
                $status = "error";
                $message = "Personeller yüklenemedi";
            }
        } catch (PDOException $ex) {
            $status = "error";
            $message = $ex->getMessage();
        }

    } else {
        $status = "error";
        $message = "Dosya uzantısı uygun değil";
    }
    if (!empty($error_message)) {
        $message = "Hatalı kayıtlar var!";
        foreach ($error_message as $error) {
            $message .= "<br>" . $error;
        }
    }

    $res = [
        "status" => $status,
        "message" => $message,
        "data" => $data,
        "error_message" => $error_message
    ];

    echo json_encode($res);
}