<?php
require_once "../../App/Helper/date.php";
require_once "../../Database/require.php";

require_once "../../Model/Missions.php";
require_once "../../Model/MissionProcess.php";

require_once "../../Model/MissionProcessMapping.php";

use App\Helper\Date;

$firm_id = $_SESSION["firm_id"];
$mission = new Missions();
$process = new MissionProcess();
$mapping = new MissionProcessMapping();
$first_map = $process->getMissionProcessFirst($firm_id);

//Göörevi kaydetme veya güncellemek için
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
            "header_id" => $_POST["header_id"],
            "start_date" => ($_POST["start_date"]) ,
            "end_date" => $_POST["end_date"],
             "priority" => $_POST["priority"],
            "description" => $_POST["description"]
        ];

        $lastInsertId = $mission->saveWithAttr($data) ?? $id;

        $status = "success";
        $message = $id > 0 ? "Güncelleme Başarılı" : "Kayıt Başarılı!!" ;

    } catch (PDOException $ex) {
        $status = "error";
        $message = $ex->getMessage();
    }
    $res = [
        "status" => $status,
        "message" => $message,
        "lastInsertId" => $lastInsertId
    ];
    echo json_encode($res);
}

//Görevi silmek için
if ($_POST["action"] == "deleteMission") {
    $id = $_POST["id"];
    try {
        $mission->delete($id);
        $status = "success";
        $message = "Görev başarıyla silindi." ;
    } catch (PDOException $e) {
        $status = "error";
        $message = $e->getMessage();
    }
    $res = [
        "status" => $status,
        "message" => $message
    ];
    echo json_encode($res);
}

//Görevi Tamamlandı veya tamamlanmadı olarak işaretlemek için
if ($_POST["action"] == "updateIsDone") {
    $id = $_POST["missionId"];
    $is_done = $_POST["isDone"];
    try {
        $mission->updateMissionStatus($id, $is_done);
        $status = "success";
        $message = "Görev başarıyla tamamlandı." ;
    } catch (PDOException $e) {
        $status = "error";
        $message = $e->getMessage();
    }
    $res = [
        "status" => $status,
        "message" => $message
    ];
    echo json_encode($res);
}

//Görevin Başlığını Sürükle bırak ile güncellemek için
if ($_POST["action"] == "updateMissionHeader") {
    $id = $_POST["mission_id"];
    $header_id = $_POST["header_id"];
    try {
        $data = [
            "id" => $id,
            "header_id" => $header_id
        ];
        $result = $mission->updateMissionHeader($id, $header_id);
        $status = "success";
        $message = "Görev başlığı başarıyla güncellendi." . $result;
    } catch (PDOException $e) {
        $status = "error";
        $message = $e->getMessage();
    }
    $res = [
        "status" => $status,
        "message" => $message
    ];
    echo json_encode($res);
}