<?php

require_once '../../Database/require.php';
require_once '../../Model/Projects.php';
require_once '../../Model/ProjectIncomeExpense.php';
require_once '../../App/Helper/helper.php';
require_once '../../App/Helper/date.php';

use App\Helper\Date;
use App\Helper\Helper;

$project = new Projects();
$incexp = new ProjectIncomeExpense();

if ($_POST['action'] == 'add_payment') {
    $id = $_POST['payment_id'];
    $project_id = $_POST['payment_project_id'];

    $data = [
        'id' => $id,
        'project_id' => $project_id,
        'tarih' => Date::Ymd($_POST['payment_date']),
        'tutar' => $_POST['payment_amount'],
        'kategori' => 3,
        'turu' => "Proje Ödemesi",
        'aciklama' => $_POST['payment_description']
    ];

    try {
        $lastInsertId = $project->saveProgressPayment($data);

         $last_payment = $project->find($lastInsertId ?? $id);
        $last_payment->tarih = Date::dmy($last_payment->tarih);
        $last_payment->tutar = Helper::formattedMoney($last_payment->tutar);
        $last_payment->kategori = Helper::getIncomeExpenseType($last_payment->kategori);

        $summary = $incexp->sumAllIncomeExpense($project_id);
        $summary->balance = Helper::formattedMoney($summary->hakedis - $summary->kesinti - $summary->odeme);
        $summary->hakedis = Helper::formattedMoney($summary->hakedis);
        $summary->kesinti = Helper::formattedMoney($summary->kesinti);
        $summary->odeme = Helper::formattedMoney($summary->odeme);


        $status = 'success';
        $message = 'Ödeme başarı ile eklendi';
    } catch (PDOException $ex) {
        $status = 'error';
        $message = $ex->getMessage();
    }

    $res = [
        'status' => $status,
        'message' => $message,
        'last_payment' => $last_payment,
        'summary' => $summary
        
    ];

    echo json_encode($res);
}
