<?php
require_once "../../Database/require.php";
require_once "../../Model/DefinesModel.php";

$Defines = new DefinesModel();

if ($_POST['action'] == "saveJobGroups") {
    $id = $_POST['id'];


    try {
        $data = [
            'id' => $id,
            'user_id' => $_SESSION['user']->id,
            'firm_id' => $_SESSION['firm_id'],
            'name' => $_POST['job_group_name'],
            'description' => $_POST['description'],
            'type_id' => 3
        ];
        $lastInsertId = $Defines->saveWithAttr($data) ?? $id;
        $message = $id == 0 ? "İş Grubu başarıyla eklendi" : "İş Grubu başarı ile güncellendi";
        $status = "success";

    } catch (PDOException $ex) {
        $status = "error";
        $message = $ex->getMessage();
    }
    $res = [
        'status' => $status,
        'message' => $message,
        'id' => $lastInsertId
    ];

    echo json_encode($res);

}

if ($_POST['action'] == "deleteJobGroups") {
    $id = $_POST['id'];
    try {
        $Defines->delete($id);
        $status = "success";
        $message = "İş Grubu başarıyla silindi";
    } catch (PDOException $ex) {
        $status = "error";
        $message = $ex->getMessage();
    }
    $res = [
        'status' => $status,
        'message' => $message
    ];
    echo json_encode($res);
}