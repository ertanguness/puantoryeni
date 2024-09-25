<?php
require_once '../../Model/Bordro.php';
require_once '../../Database/require.php';
require_once '../../App/Helper/date.php';
require_once '../../App/Helper/helper.php';


use App\Helper\Helper;
use App\Helper\Date;

$wagecut = new Bordro();

if ($_POST['action'] == 'saveWageCut') {
    $id = $_POST['wage_cut_id'];
    $person_id = $_POST['person_id_wage_cut'];
    $month = $_POST['wage_cut_month'];
    $year = $_POST['wage_cut_year'];

    // Sayıları birleştirerek string oluşturun
    $dateString = sprintf('%2d%02d15',  $year,$month);

    $data = [
        'id' => $id,
        "user_id" => $_SESSION['user']->id,
        'person_id' => $person_id,
        'gun' => (int)$dateString,
        "ay" => $month,
        "yil" => $year,
        "kategori" => 2,
        'turu' => $_POST['wage_cut_type'],
        'tutar' => $_POST['wage_cut_amount'],
        'aciklama' => $_POST['wage_cut_description'],
    ];

   $lastInsertId= $wagecut->saveWithAttr($data);
   
    // Son eklenen kaydın bilgileri formatlanır
    $wagecutData = $wagecut->getPersonIncomeExpensePayment($lastInsertId);

    // Kaydedilen verinin türü getirilir(Ödeme, Kesinti, Gelir) (Gelir)
    $wagecutData->kategori = Helper::getIncomeExpenseType($wagecutData->kategori);

    // Tutar formatlanır
    $wagecutData->tutar = Helper::formattedMoney($wagecutData->tutar);

    $income_expense = $wagecut->getIncomeExpenseData($person_id);



    $status = 'success';
    $message = 'Başarıyla eklendi';

    $res = [
        'status' => $status,
        'message' => $message,
        'wagecut_data' => $wagecutData,
        'income_expense' => $income_expense
    ];

    echo json_encode($res);
}