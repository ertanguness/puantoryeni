<?php
require_once "../../Database/require.php";
require_once "../../Model/Cases.php";
require_once "../../Model/CaseTransactions.php";

$caseObj = new Cases();
$CaseTransactions = new CaseTransactions();

if ($_POST["action"] == "saveCase") {
    $id = $_POST["id"];
    //eğer id 0 ve firmanın hiç kasası yoksa varsayılan kasa yap
    if ($id == 0 && $caseObj->countCaseByFirm() == 0) {
        $_POST["default_case"] = 1;
    }
    $data = [
        "id" => $id,
        "case_name" => $_POST["case_name"],
        "account_id" => $_SESSION["user"]->id,
        "firm_id" => $_SESSION["firm_id"],
        "start_budget" => $_POST["start_budget"],
        "bank_name" => $_POST["bank_name"],
        "branch_name" => $_POST["branch_name"],
        "case_money_unit" => $_POST["case_money_unit"],
        "description" => $_POST["description"],
        "isDefault" => $_POST["default_case"] ?? 0,
    ];
    try {
        $lastInsertId = $caseObj->saveWithAttr($data);
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
    $id = $_POST["case_id"];

    $caseObj->setDefaultCase($id);
    $res = [
        "status" => "success",
        "message" => "Varsayılan kasa başarıyla ayarlandı."
    ];
    echo json_encode($res);

}