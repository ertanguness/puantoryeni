<?php

use App\Helper\Security;

require_once "../../Database/require.php";
require_once "../../Model/Projects.php";

$project = new Projects();

if ($_POST['action'] == "addPersonToProject") {

    $record_id = $project->findById($_POST['project_id']) ;

    $data = [
        "id" => $record_id ,
        'project_id' =>($_POST['project_id']),
        'person_id' => $_POST['person_id'],
        "state" => 1

    ];

    try {

        $project->addPersontoProject($data);
        $status = "success";
        if ($record_id == 0) {
            $message = "Personel başarı ile projeye eklendi";
        } else {
            $message = "Personeller başarı ile güncellendi";
        }
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
