<?php
require_once "../../Database/require.php";
require_once "../../Model/CaseTransactions.php";
require_once "../../App/Helper/date.php";
require_once "../../App/Helper/helper.php";
require_once  "../../App/Helper/financial.php";

use App\Helper\Helper;
use App\Helper\Date;

$ct = new CaseTransactions();
$financial = new Financial();

if ($_POST["action"] == "saveTransaction") {
    $id = $_POST["transaction_id"];

    $data = [
        "id" => $id,
        "date" => date("Y-m-d H:i:s"),
        "type_id" => $_POST["transaction_type"],
        "case_id" => $_POST["case_id"],
        "amount" =>$_POST["amount"],
        "amount_money" => $_POST["amount_money"],
        "description" => $_POST["description"],
    ];

    try {
        $lastInsertId = $ct->saveWithAttr($data);
        $status = "success";
        if ($id == 0) {
            $message = "Kasa Hareketi başarıyla eklendi" ;
        } else {

            $message = "Kasa Hareketi başarıyla güncellendi";
        }

        //Eklenen kaydın bilgilerini getir
        $transaction = $ct->find($lastInsertId);
        //Kayıt alanlarında düzenleme
        foreach ($transaction as $key => $value) {
            if ($key == "date") {
                $transaction->$key = Date::dmY($value);
            } elseif ($key == "type_id") {
                $transaction->$key = $value == 1 ? "Gelir" : "Gider";
            } elseif ($key == "case_id") {
                $transaction->$key = $financial->getCaseName($value);
            } elseif ($key == "amount") {
                $transaction->$key = Helper::formattedMoney($value, $transaction->amount_money ?? 1);
            }
        }
    } catch (PDOException $ex) {
        $status = "error";
        $message = $ex->getMessage();
    }

    $res = [
        "status" => $status,
        "message" => $message,
        "transaction" => $transaction
    ];
    echo json_encode($res);
}

if ($_POST["action"] == "deleteTransaction") {
    $id = $_POST["id"];
    try {
        $ct->delete($id);
        $status = "success";
        $message = "Kasa hareketi başarıyla silindi.";
    } catch (PDOException $ex) {
        $status = "error";
        $message = "Kasa hareketi bir hata oluştu.";
    }
    $res = [
        "status" => $status,
        "message" => $message,
    ];
    echo json_encode($res);
}

// $dbInstance->disconnect();
