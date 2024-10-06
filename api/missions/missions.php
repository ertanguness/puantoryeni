<?php

require_once "../../Database/require.php";

require_once "../../Model/Missions.php";
require_once "../../Model/MissionProcess.php";

require_once "../../Model/MissionProcessMapping.php";

$firm_id = $_SESSION["firm_id"];
$mission = new Missions();
$process = new MissionProcess();
$mapping = new MissionProcessMapping();
$first_map = $process->getMissionProcessFirst($firm_id);


if ($_POST["action"] == "saveMission") {
    $id = $_POST["id"];


    //Görev Atananan kullanıcılar
    $users = $_POST["user_ids"];
    $user_ids = "";
    foreach ($users as $user) {
        $user_ids .= $user . ",";
    }
    $user_ids = rtrim($user_ids, ",");

    try {
        $data = [
            "id" => $id,
            "name" => $_POST["name"],
            "firm_id" => $_SESSION["firm_id"],
            "user_ids" => $user_ids,
            "priority" => $_POST["priority"],
            "description" => $_POST["description"]
        ];

        $lastInsertId = $mission->saveWithAttr($data) ?? $id;

        if ($id == 0) {
            $mapData = [
                "mission_id" => $lastInsertId,
                "process_id" => $first_map->id,
            ];
            $mapping->saveWithAttr($mapData);
        }

        $status = "success";
        $message = $id > 0 ? "Güncelleme Başarılı" : "Kayıt Başarılı!!" ;

    } catch (PDOException $ex) {
        $status = "error";
        $message = $ex->getMessage();
    }
    $res = [
        "status" => $status,
        "message" => $message
    ];
    echo json_encode($res);
}