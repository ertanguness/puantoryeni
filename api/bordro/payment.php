<?php
require_once '../../Model/Bordro.php';
require_once '../../Model/Puantaj.php';
require_once '../../Database/require.php';
require_once '../../App/Helper/date.php';
require_once '../../App/Helper/helper.php';

use App\Helper\Date;
use App\Helper\Helper;

$payment = new Bordro();
$puantaj = new Puantaj();

if ($_POST['action'] == 'savePayment') {
    $id = $_POST['id'];
    $person_id = $_POST['person_id_payment'];
    $month = $_POST['payment_month'];
    $year = $_POST['payment_year'];

    // Sayıları birleştirerek string oluşturun
    $dateString = sprintf('%2d%02d15', $year, $month);

    $data = [
        'id' => $id,
        'user_id' => $_SESSION['user']->id,
        'person_id' => $person_id,
        'gun' => (int) $dateString,
        'ay' => $month,
        'yil' => $year,
        'kategori' => 3,  // Personel Ödemesi
        'turu' => $_POST['payment_type'],
        'tutar' => $_POST['payment_amount'],
        'aciklama' => $_POST['payment_description'],
    ];

    try {
        $lastInsertId = $payment->saveWithAttr($data);

        $last_payment = $payment->getPersonIncomeExpensePayment($lastInsertId);

        // Personelin, maas_gelir_kesinti tablosundaki ödeme, kesinti ve gelir toplamlarını getirir
        $income_expence = $payment->getPersonTotalIncomeExpensePayment($person_id);

        // Puantaj tablosundaki toplam hakediş toplamlarını getirir
        $income_puantaj = $puantaj->getPuantajIncomeByPerson($person_id);

        // Toplam gelir ve puantajdaki toplam hakediş toplamını alır
        $total_income_puantaj = $income_expence->total_income + $income_puantaj->total_income;

        $balance = Helper::formattedMoney($total_income_puantaj - $income_expence->total_payment - $income_expence->total_expense);  // Bakiye
        $income_expence->total_income = Helper::formattedMoney($total_income_puantaj);  // Toplam gelir
        $income_expence->total_montly_income = Helper::formattedMoney($total_montly_income);  // Toplam gelir
        $income_expence->total_payment = Helper::formattedMoney($income_expence->total_payment);  // Toplam ödeme
        $income_expence->total_expense = Helper::formattedMoney($income_expence->total_expense);  // Toplam gider
        $income_expence->balance = $balance;  // Bakiye

        // Son eklenn kaydın bilgileri formatlanır
        $last_payment->kategori = Helper::getIncomeExpenseType($last_payment->kategori);
        $last_payment->tutar = Helper::formattedMoney($last_payment->tutar);

        $status = 'success';
        $message = 'Başarıyla eklendi';
    } catch (PDOException $e) {
        $status = 'error';
        $message = $e->getMessage();
    }

    $res = [
        'status' => $status,
        'message' => $message,
        'payment' => $last_payment,
        'income_expence' => $income_expence ?? [],
    ];

    echo json_encode($res);
}
if ($_POST['action'] == 'deletePayment') {
    $id = $_POST['id'];

    try {
        $db->beginTransaction();

        // Ödeme bilgilerini getirir
        $paymentInfo = $payment->getPersonIncomeExpensePayment($id);

         $payment->delete($id);
        $status = 'success';
        $message = 'Başarıyla silindi.';

        //Ödeme bilgilerindeki person_id alınır, 
        //Personelin, maas_gelir_kesinti tablosundaki ödeme, kesinti ve gelir toplamlarını getirir
        $income_expence = $payment->getPersonTotalIncomeExpensePayment($paymentInfo->person_id);

        $db->commit();
    } catch (PDOException $e) {
        $db->rollBack();
        $status = 'error';
        $message = $e->getMessage();
    }
    $res = [
        'status' => $status,
        'message' => $message,
        'income_expence' => $income_expence ,
    ];
    echo json_encode($res);
}

// if ($_POST['action'] == 'deletePayment') {
//     $id = $_POST['id'];
//     $status = $message = '';
//     try {
//         $db->beginTransaction();
//         if($id == null || $id == 0 || $id = "undefined") {
//             $status = 'error';
//             $message= ' ID numarası tanımsız.İşlem yapılamadı!' ;

//         }else{
//             $payment->delete($id) ;
//             $status = 'success';
//             $message = 'Başarıyla silindi.' . $id;

//         }

//         $db->commit();
//     } catch (PDOException $e) {
//         $db->rollBack();
//         $status = 'error';
//         $message = $e->getMessage();
//     }
//     $res = [
//         'status' => $status,
//         'message' => $message
//     ];
//     echo json_encode($res);
// }
