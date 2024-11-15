<?php


require_once "../../Database/require.php";
require_once "../../Model/Projects.php";
require_once "../../App/Helper/security.php";

use App\Helper\Security;
$project = new Projects();

if ($_POST['action'] == "saveProject") {
    $id =$_POST["id"] != 0 ? Security::decrypt($_POST["id"]) : 0;

    $data = [
        "id" => $id,
        "firm_id" => $_SESSION['firm_id'],
        "company_id" => Security::decrypt($_POST['project_company']),
        'project_name' => Security::escape($_POST['project_name']),
        'start_date' => Security::escape($_POST['start_date']),
        'city' => Security::escape($_POST['project_city']),
        'town' => Security::escape($_POST['project_town']),
        'status' => Security::escape($_POST['project_status']),
        'email' => Security::escape($_POST['email']),
        'phone' => Security::escape($_POST['phone']),
        'account_number' => Security::escape($_POST['account_number']),
        'address' => Security::escape($_POST['address']),
    ];
    //Yeni kayıt esnasında başlangıç bütçesi alınır
    if(isset($_POST["budget"])){
        $data["budget"] = $_POST["budget"];
    };
    try {
        $lastInsertId = $project->saveWithAttr($data) ?? $_POST['id'];
        $status = "success";
        if ($id > 0) {
            $message = "Proje başarıyla güncellendi";
        } else {
            $message = "Proje başarıyla eklendi";
        }
    } catch (PDOException $ex) {
        $status = "error";
        $message = $ex->getMessage();
    }

    $res = [
        'status' => $status,
        'message' => $message,
        'lastInsertId' => $lastInsertId ?? 0
    ];
    echo json_encode($res);
}

if ($_POST['action'] == "deleteProject") {
    $id = $_POST['id'] ;
    //projede kayıtlı çalışma var mı kontrol et
    $isExistPuantaj = $project->isExistPuantaj($id);
    if ($isExistPuantaj) {
        $status = "error";
        $message = "Projede kayıtlı çalışma var. Projeyi silemezsiniz.";
        $res = [
            'status' => $status,
            'message' => $message,
        ];
        echo json_encode($res);
        exit;
    }
    try {
        $db->beginTransaction();
        $project->delete($id);
        $status = "success";
        $message = "Proje başarıyla silindi";
        $db->commit();
    } catch (PDOException $ex) {
        $status = "error";
        $message = $ex->getMessage();
        $db->rollBack();
    }

    $res = [
        'status' => $status,
        'message' => $message,
    ];
    echo json_encode($res);
}