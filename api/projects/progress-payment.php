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

if ($_POST['action'] == 'add_progress_payment') {
    $id = $_POST['progress_payment_id'];
    $project_id = $_POST['progress_payment_project_id'];

    $data = [
        'id' => $id,
        'project_id' => $project_id,
        'firm_id' => $_SESSION['firm_id'],
        'tarih' => Date::Ymd($_POST['progress_payment_date']),
        'tutar' => $_POST['progress_payment_amount'],
        'kategori' => 6,
        'turu' => 'Proje Hakediş',
        'aciklama' => $_POST['progress_payment_description']
    ];

    try {
        $lastInsertId = $project->saveProgressPayment($data);

        $last_progress_payment = $project->find($lastInsertId);
        $last_progress_payment->tarih = Date::dmy($last_progress_payment->tarih);
        $last_progress_payment->tutar = Helper::formattedMoney($last_progress_payment->tutar);
        $last_progress_payment->kategori = Helper::getIncomeExpenseType($last_progress_payment->kategori);

        $summary = $incexp->sumAllIncomeExpense($project_id);
        $summary->balance = Helper::formattedMoney($summary->hakedis - $summary->kesinti - $summary->odeme);
        $summary->hakedis = Helper::formattedMoney($summary->hakedis);
        $summary->kesinti = Helper::formattedMoney($summary->kesinti);
        $summary->odeme = Helper::formattedMoney($summary->odeme);

        $status = 'success';
        $message = 'Hakediş başarı ile eklendi';
    } catch (PDOException $ex) {
        $status = 'error';
        $message = $ex->getMessage();
    }

    $res = [
        'status' => $status,
        'message' => $message,
        'progress_payment' => $last_progress_payment,
        'summary' => $summary
    ];

    echo json_encode($res);
}
