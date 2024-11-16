<?php
require_once "../../Database/require.php";
require_once "../../Model/Cases.php";
require_once "../../Model/CaseTransactions.php";
require_once "../../App/Helper/date.php";
require_once "../../App/Helper/helper.php";
require_once "../../App/Helper/financial.php";
require_once "../../Model/DefinesModel.php";
require_once "../../App/Helper/security.php";
require_once "../../Model/Auths.php";



use App\Helper\Helper;
use App\Helper\Date;
use App\Helper\Security;

$Auths = new Auths();
$cases = new Cases();
$ct = new CaseTransactions();
$financial = new Financial();
$define = new DefinesModel();


$Auths->checkFirmReturn();

if ($_POST["action"] == "saveTransaction") {
    $id = $_POST["transaction_id"];

    //Kasa hareketi ekleme yetkisi var mı?
    $Auths->hasPermission("income_expense_add_update");

    $data = [
        "id" => 0,
        "date" => date("Y-m-d H:i:s"),
        "type_id" => $_POST["transaction_type"],
        "sub_type" => $_POST["transaction_type"],
        "case_id" => Security::decrypt($_POST["case_id"]),
        "amount" => Helper::formattedMoneyToNumber($_POST["amount"]),
        "amount_money" => $_POST["amount_money"],
        "description" => Security::escape($_POST["description"]),
    ];

    try {
        $lastInsertId = $ct->saveWithAttr($data);
        $status = "success";
        if ($id == 0) {
            $message = "Kasa Hareketi başarıyla eklendi";
        } else {

            $message = "Kasa Hareketi başarıyla güncellendi";
        }

        //Eklenen kaydın bilgilerini getir
        $transaction = $ct->find(Security::decrypt($lastInsertId));
        //Kayıt alanlarında düzenleme
        foreach ($transaction as $key => $value) {
            if($key == "id"){
                $transaction->id = Security::encrypt($value);
            }else if ($key == "date") {
                $transaction->$key = Date::dmY($value);
            } elseif ($key == "type_id") {
                $transaction->$key = $value == 1 ? "Gelir" : "Gider";
            } elseif ($key == "case_id") {
                $transaction->$key = $cases->find($value)->case_name;
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

    //Kasa hareketi silme yetkisi var mı?
    $Auths->hasPermissionReturn("delete_income_expense");
      

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

if ($_POST["action"] == "getSubTypes") {
    $type = $_POST["type"];
    $subTypes = $define->getIncExpTypesByFirmandType($type);
    $res = [

        "subTypes" => $subTypes
    ];
    echo json_encode($res);
}


//Projeden Ödeme Al
if($_POST["action"] == "getPaymentFromProject"){
    $project_id = $_POST["fp_project_name"];

    $amount = Helper::formattedMoneyToNumber($_POST["fp_amount"]);
    $date = Date::ymd($_POST["fp_action_date"]);
    $description = Security::escape($_POST["fp_description"]);

    //Kasa hareketi ekleme yetkisi var mı?
    //$Auths->hasPermission("income_expense_add_update");

    $data = [
        "id" => 0,
        "date" => $date,
        "type_id" => 2,
        "sub_type" => 2,
        "case_id" => ($_POST["fp_cases"]),
        "amount" => $amount,
        "amount_money" => 1,
        "description" => $description,
    ];

    try {
        $lastInsertId = $ct->saveWithAttr($data);
        $status = "success";
        $message = "Ödeme başarıyla alındı";

        //Eklenen kaydın bilgilerini getir
        // $transaction = $ct->find(Security::decrypt($lastInsertId));
        // //Kayıt alanlarında düzenleme
        // foreach ($transaction as $key => $value) {
        //     if($key == "id"){
        //         $transaction->id = Security::encrypt($value);
        //     }else if ($key == "date") {
        //         $transaction->$key = Date::dmY($value);
        //     } elseif ($key == "type_id") {
        //         $transaction->$key = $value == 1 ? "Gelir" : "Gider";
        //     } elseif ($key == "case_id") {
        //         $transaction->$key = $cases->find($value)->case_name;
        //     } elseif ($key == "amount") {
        //         $transaction->$key = Helper::formattedMoney($value, $transaction->amount_money ?? 1);
        //     }
        // }
    } catch (PDOException $ex) {
        $status = "error";
        $message = $ex->getMessage();
    }

    $res = [
        "status" => $status,
        "message" => $message,
       
    ];
    echo json_encode($res);
}
