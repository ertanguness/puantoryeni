<?php
require_once "../../Database/require.php";
require_once "../../Model/Cases.php";

$caseObj = new Cases();

if ($_POST["action"] == "saveCase") {
    $id = $_POST["id"];
    $data = [
        "id" => $id,
        "case_name" => $_POST["case_name"],
        "account_id" => $_SESSION["user"]->id,
        "company_id" => $_POST["firm_company"],
        "start_budget" => $_POST["start_budget"],
        "bank_name" => $_POST["bank_name"],
        "branch_name" => $_POST["branch_name"],
        "description" => $_POST["description"],
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
        $message = "Kasa kaydedilirken bir hata oluştu.";
    }
    $res = [
        "status" => $status,
        "message" => $message,
    ];
    echo json_encode($res);
}

if ($_POST["action"] == "deleteCase") {
    $id = $_POST["id"];
    try {
        $caseObj->delete($id);
        $status = "success";
        $message = "Kasa başarıyla silindi.";
    } catch (PDOException $ex) {
        $status = "error";
        $message = "Kasa silinirken bir hata oluştu.";
    }
    $res = [
        "status" => $status,
        "message" => $message,
    ];
    echo json_encode($res);
}

if($_POST["action"] == "defaultCase"){
    $id = $_POST["case_id"];

    $caseObj->setDefaultCase($id);
    $res = [
        "status" => "success",
        "message" => "Varsayılan kasa başarıyla ayarlandı."
    ];
    echo json_encode($res);
    
}