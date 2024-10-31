<?php
require_once '../../Model/Bordro.php';
require_once '../../Model/Puantaj.php';
require_once '../../Database/require.php';
require_once '../../App/Helper/date.php';
require_once '../../App/Helper/helper.php';
require_once '../../App/Helper/security.php';
require_once "../../Model/Auths.php";

use App\Helper\Date;
use App\Helper\Helper;
use App\Helper\Security;
$Auths = new Auths();

$payment = new Bordro();
$puantaj = new Puantaj();


$Auths->checkFirmReturn();

if ($_POST['action'] == 'savePayment') {
    //Ödeme Ekleme yetkisi var mı kontrol et
    $Auths->hasPermissionReturn("make_staff_payment");

    $id = $_POST['id'];
    $person_id = Security::decrypt($_POST['person_id_payment']);
    $month = $_POST['payment_month'];
    $year = $_POST['payment_year'];

    // Sayıları birleştirerek string oluşturun
    $dateString = sprintf('%2d%02d15', $year, $month);

    $data = [
        'id' => 0,
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

        $last_payment = $payment->getPersonIncomeExpensePayment(Security::decrypt($lastInsertId));

        // // Personelin, maas_gelir_kesinti tablosundaki ödeme, kesinti ve gelir toplamlarını getirir
        $income_expense = $payment->sumAllIncomeExpense($person_id);

        // Son eklenen kaydın bilgileri formatlanır
        $last_payment->kategori = Helper::getIncomeExpenseType($last_payment->kategori);
        $last_payment->gun = Date::dmY($last_payment->gun);
        $last_payment->tutar = Helper::formattedMoney($last_payment->tutar);

        $income_expense->total_income = Helper::formattedMoney($income_expense->total_income ?? 0);  // Toplam gelir
        $income_expense->total_payment = Helper::formattedMoney($income_expense->total_payment ?? 0);  // Toplam ödeme
        $income_expense->total_expense = Helper::formattedMoney($income_expense->total_expense ?? 0);  // Toplam gider
        $income_expense->balance = Helper::formattedMoney($payment->getBalance($person_id));  // Bakiye


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
        'income_expense' => $income_expense ?? [],
    ];

    echo json_encode($res);
}

if ($_POST['action'] == 'deletePayment') {

    //Ödeme Silme Yetkisi var mı kontrol et
    $Auths->hasPermissionReturn("delete_staff_payment");

    $id = $_POST['id'];

    try {
        $db->beginTransaction();

        // Ödeme bilgilerini getirir
        $paymentInfo = $payment->getPersonIncomeExpensePayment(Security::decrypt($id));

        //Gelen id'nin encrypt edilmiş olup olmadığını kontrol edin
        if ($payment->delete($id)) {
            $status = 'success';
            $message = 'Başarıyla silindi.';
        } else {
            $status = 'error';
            $message = 'Silinirken bir hata oluştu.';
        }
        ;

        // Ödeme bilgilerindeki person_id alınır,
        // Personelin, maas_gelir_kesinti tablosundaki ödeme, kesinti ve gelir toplamlarını getirir
        $income_expense = $payment->sumAllIncomeExpense($paymentInfo->person_id);

        //Bakiye hesaplanır
        $balance = Helper::formattedMoney($income_expense->total_income - $income_expense->total_payment - $income_expense->total_expense);  // Bakiye

        //Toplam Gelir
        $income_expense->total_income = Helper::formattedMoney($income_expense->total_income ?? 0);

        // Toplam ödeme 
        $income_expense->total_payment = Helper::formattedMoney($income_expense->total_payment ?? 0);

        // Toplam gider
        $income_expense->total_expense = Helper::formattedMoney($income_expense->total_expense ?? 0);

        // Bakiye, değişkenine atama yapılır
        $income_expense->balance = $balance;

        $db->commit();
    } catch (PDOException $e) {
        $db->rollBack();
        $status = 'error';
        $message = $e->getMessage();
    }
    $res = [
        'status' => $status,
        'message' => $message,
        'income_expense' => $income_expense,
    ];
    echo json_encode($res);
}
