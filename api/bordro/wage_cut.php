<?php
require_once '../../Model/Bordro.php';
require_once '../../Database/require.php';
require_once '../../App/Helper/date.php';

use App\Helper\Date;

$wage_cut = new Bordro();

if ($_POST['action'] == 'saveWageCut') {
    $id = $_POST['id'];
    $month = $_POST['wage_cut_month'];
    $year = $_POST['wage_cut_year'];

    // Sayıları birleştirerek string oluşturun
    $dateString = sprintf('%2d%02d15',  $year,$month);

    $data = [
        'id' => $id,
        "user_id" => $_SESSION['user']->id,
        'person_id' => $_POST['person_id'],
        'gun' => (int)$dateString,
        "ay" => $month,
        "yil" => $year,
        "kategori" => 2,
        'turu' => $_POST['wage_cut_type'],
        'tutar' => $_POST['wage_cut_amount'],
        'aciklama' => $_POST['wage_cut_description'],
    ];

    $wage_cut->saveWithAttr($data);

    $status = 'success';
    $message = 'Başarıyla eklendi';

    $res = [
        'status' => $status,
        'message' => $message,
    ];

    echo json_encode($res);
}