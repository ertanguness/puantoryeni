<?php
require_once '../Database/require.php';
require_once '../Model/Puantaj.php';
require_once '../Model/Persons.php';
require_once '../Database/db.php';
require_once '../App/Helper/date.php';

use App\Helper\Date;

$puantajObj = new Puantaj();
$person = new Persons();

if ($_POST['action'] == 'savePuantaj') {
    $status = '';
    $message = '';

    $json_data = json_decode($_POST['data'], true);
    $error_wages = [];
    foreach ($json_data as $person_key => $person_item) {
        // puantajId'nin boş olmadığını kontrol et
        $ucret = $person->getDailyWages($person_key)->daily_wages / 8;
        // Eğer personelin günlük ücreti 0 ise error_wages dizisine ekle
        if ($ucret == 0 || $ucret == '') {
            $error_wages[] = $person->getPersonById($person_key, 'full_name')->full_name;
        }
        foreach ($person_item as $puantaj_key => $puantaj_item) {
            $id = $puantajObj->getPuantajId($person_key, $puantaj_key);
            if ($puantaj_item['puantajId'] == 0) {
                // Eğer puantajId 0 ise personelin o gün için puantajı olmadığı için
                // hesaplama yapılmaması için o kayıt silinir
                $puantajObj->delete($id);
            } else if (!empty($puantaj_item['puantajId'])) {
                $saat = $puantajObj->getPuantajSaati($puantaj_item['puantajId']);

                $tutar = $saat * $ucret;

                $data = [
                    'id' => $id, // Puantaj tablosundaki id,
                    'person' => $person_key, // Personel id
                    'project_id' => $puantaj_item['project_id'],  // Proje id
                    'puantaj_id' => $puantaj_item['puantajId'], // Puantaj id
                    'gun' => $puantaj_key,  // Tarih
                    'saat' => $saat, // Puantaj saati
                    'tutar' => $tutar, // Tutar
                ];

                try {
                    // Veriyi modele gönder
                    $lastInsertId = $puantajObj->saveWithAttr($data);
                    $status = 'success';
                    if (count($error_wages) > 0) {
                        $message = 'Puantaj başarıyla güncellendi.<br>Fakat şu personellerin günlük ücreti 0 olduğu için hesaplama yapılamadı: <strong> <br>' . implode(', ', $error_wages) . '</strong>';
                    } else {
                        $message = 'Puantaj başarıyla güncellendi';
                    }
                } catch (Exception $e) {
                    // Hata yönetimi
                    $status = 'error';
                    $message = 'Bir hata oluştu: ' . $e->getMessage();
                }
            }
        }
    }

    $res = [
        'status' => $status,
        'message' => $message,
        'error_wages' => $error_wages
    ];

    echo json_encode($res);
}
