<?php
require_once "../../Database/require.php";
require_once "../../Model/Projects.php";

$project = new Projects();

if ($_POST['action'] == "saveProject") {
    $id = $_POST['id'] ?? 0;

    $data = [
        "id" => $id,
        "company_id" => $_POST['firm_id'],
        'project_name' => $_POST['project_name'],
        'budget' => $_POST['budget'],
        'city' => $_POST['project_city'],
        'town' => $_POST['project_town'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'account_number' => $_POST['account_number'],
        'address' => $_POST['address'],
    ];
    try {
        $lastInsertId = $project->saveWithAttr($data);
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
