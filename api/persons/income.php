<?php
require_once '../../Model/Bordro.php';
require_once '../../Database/require.php';
require_once '../../App/Helper/date.php';
require_once '../../App/Helper/helper.php';

use App\Helper\Date;
use App\Helper\Helper;

$income = new Bordro();

if ($_POST['action'] == 'saveIncome') {
    $id = $_POST['id'];
    $person_id = $_POST['person_id_income'];
    $month = $_POST['income_month'];
    $year = $_POST['income_year'];

    // Sayıları birleştirerek string oluşturun
    $dateString = sprintf('%2d%02d15', $year, $month);

    $data = [
        'id' => $id,
        'user_id' => $_SESSION['user']->id,
        'person_id' => $person_id,
        'gun' => (int) $dateString,
        'ay' => $month,
        'yil' => $year,
        'kategori' => 1,
        'turu' => $_POST['income_type'],
        'tutar' => $_POST['income_amount'],
        'aciklama' => $_POST['income_description'],
    ];

    $lastInsertId = $income->saveWithAttr($data);

    // Son eklenen kaydın bilgileri formatlanır
    $incomeData = $income->getPersonIncomeExpensePayment($lastInsertId);

    // Kaydedilen verinin türü getirilir(Ödeme, Kesinti, Gelir) (Gelir)
    $incomeData->kategori = Helper::getIncomeExpenseType($incomeData->kategori);

    // Tutar formatlanır
    $incomeData->tutar = Helper::formattedMoney($incomeData->tutar);

    $income_expense = $income->getIncomeExpenseData($person_id);



    $status = 'success';
    $message = 'Başarıyla eklendi';

    $res = [
        'status' => $status,
        'message' => $message,
        'income_data' => $incomeData,
        'income_expense' => $income_expense
    ];

    echo json_encode($res);
}
