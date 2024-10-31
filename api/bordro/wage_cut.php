<?php
require_once '../../Model/Bordro.php';
require_once '../../Database/require.php';
require_once '../../App/Helper/date.php';
require_once '../../App/Helper/security.php';
require_once '../../App/Helper/auths.php';

$Auths = new Auths();



use App\Helper\Date;
use App\Helper\Security;

$wage_cut = new Bordro();

if ($_POST['action'] == 'saveWageCut') {
    $id = $_POST['wage_cut_id'];
    $month = $_POST['wage_cut_month'];
    $year = $_POST['wage_cut_year'];

    // Sayıları birleştirerek string oluşturun
    $dateString = sprintf('%2d%02d15', $year, $month);

    $data = [
        'id' => $id,
        "user_id" => $_SESSION['user']->id,
        'person_id' => Security::decrypt($_POST['person_id_wage_cut']),
        'gun' => (int) $dateString,
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