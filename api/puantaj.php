<?php
require_once "../Database/require.php";
require_once "../Model/Puantaj.php";
require_once "../Model/Persons.php";
require_once "../Database/db.php";
require_once "../App/Helper/date.php";

use App\Helper\Date;

$puantajObj = new Puantaj();
$person = new Persons();

if ($_POST["action"] == "savePuantaj") {
    $status = "";
    $message = "";

    $json_data = json_decode($_POST["data"], true);
    foreach ($json_data as $person_key => $person_item) {
        // puantajId'nin boş olmadığını kontrol et
        foreach ($person_item as $puantaj_key => $puantaj_item) {
            if (!empty($puantaj_item['puantajId'])) {
                $saat = $puantajObj->getPuantajSaati($puantaj_item["puantajId"]);
                $ucret = $person->getDailyWages($person_key)->daily_wages / 8;

                $tutar = $saat * $ucret;

                $data = [
                    "id" => $puantajObj->getPuantajId($person_key,$puantaj_key),
                    "person" => $person_key,
                    "project_id" => $puantaj_item["project_id"],
                    "puantaj_id" => $puantaj_item["puantajId"],
                    "gun" =>  $puantaj_key,
                    "saat" => $saat,
                    "tutar"  => $tutar,
                ];

                try {
                    // Veriyi modele gönder
                    $lastInsertId = $puantajObj->saveWithAttr($data);
                    $status = "success";
                    $message =  "Puantaj başarıyla güncellendi";
                } catch (Exception $e) {
                    // Hata yönetimi
                    $status = "error";
                    $message = "Bir hata oluştu: " . $e->getMessage();
                }
            }
        }
    }
    $res = [
        "status" => $status,
        "message" => $message
    ];

    echo json_encode($res);
}
