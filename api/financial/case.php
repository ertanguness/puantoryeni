<?php


require_once "../../Database/require.php";
require_once "../../Model/Cases.php";
require_once "../../Model/CaseTransactions.php";
require_once "../../App/Helper/security.php";
require_once "../../Model/Auths.php";

use App\Helper\Security;
$Auths = new Auths();

$Auths->checkFirmReturn();

$caseObj = new Cases();
$CaseTransactions = new CaseTransactions();

if ($_POST["action"] == "saveCase") {

    //
    $Auths->hasPermission("cash_register_add_update");

    $id = $_POST["id"] != 0 ? Security::decrypt($_POST["id"]) : 0;

    //eğer id 0 ve firmanın hiç kasası yoksa varsayılan kasa yap
    if ($id == 0 && $caseObj->countCaseByFirm() == 0) {
        $_POST["default_case"] = 1;
    }

    //eğer varsayılan kasa yapılacaksa diğer kasaların varsayılanlığını kaldır
    if (isset($_POST["default_case"]) && $_POST["default_case"] == 'on') {
        $caseObj->removeDefaultCase();
        $_POST["default_case"] = 1;
    } else {
        $_POST["default_case"] = 0;
    }
    $data = [
        "id" => $id,
        "case_name" => Security::escape($_POST["case_name"]),
        "account_id" => Security::escape($_SESSION["user"]->id),
        "firm_id" => Security::escape($_SESSION["firm_id"]),
        "bank_name" => Security::escape($_POST["bank_name"]),
        "branch_name" => Security::escape($_POST["branch_name"]),
        "case_money_unit" => Security::escape($_POST["case_money_unit"]),
        "description" => Security::escape($_POST["description"]),
        "isDefault" => Security::escape($_POST["default_case"]),

    ];
    if(isset($_POST["start_budget"]) && $_POST["start_budget"] != ""){
        $data["start_budget"] = Security::escape($_POST["start_budget"]);
    }   


    try {
        $lastInsertId = ($caseObj->saveWithAttr($data)) ?? $_POST["id"];
        $status = "success";
        if ($id == 0) {
            $message = "Kasa başarıyla kaydedildi.";
        } else {
            $message = "Kasa başarıyla güncellendi.";
        }
    } catch (PDOException $ex) {
        $status = "error";
        $message = "Kasa kaydedilirken bir hata oluştu." . $ex->getMessage();
    }
    $res = [
        "status" => $status,
        "message" => $message,
        "lastid" => $lastInsertId
    ];
    echo json_encode($res);
}

if ($_POST["action"] == "deleteCase") {
    $id = $_POST["id"];

    //Kasa silme yetkisi var mı kontrol et
    $Auths->hasPermission("cash_delete");

    //Eğer kasa varsayılan ise silinemez
    $caseObj->checkDefaultCase(Security::decrypt($id));

    try {
        $db->beginTransaction();
        $caseObj->delete($id);
        $CaseTransactions->deleteCaseTransactions($id);

        $status = "success";
        $message = "Kasa başarıyla silindi.";
        $db->commit();
    } catch (PDOException $ex) {
        $db->rollBack();
        $status = "error";
        $message = "Kasa silinirken bir hata oluştu.";
    }
    $res = [
        "status" => $status,
        "message" => $message,
    ];
    echo json_encode($res);
}

if ($_POST["action"] == "defaultCase") {
    $id = Security::decrypt($_POST["case_id"]);

    $caseObj->setDefaultCase($id);
    $res = [
        "status" => "success",
        "message" => "Varsayılan kasa başarıyla ayarlandı."
    ];
    echo json_encode($res);

}