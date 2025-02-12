<?php
require_once "../../Database/require.php";

require_once "../../Model/MissionHeaders.php";

$mission = new MissionHeaders();

if ($_POST["action"] == "saveMissionHeaders") {
    $id = $_POST["id"];

    try {
        $data = [
            "id" => $id,
            "header_name" => $_POST["header_name"],
            "firm_id" => $_SESSION["firm_id"],
            "status" => $_POST["status"],
            "user_id" => $_SESSION["user"]->id,
            "description" => $_POST["description"]
        ];

        $lastInsertId = $mission->saveWithAttr($data) ?? $id;

        $status = "success";
        $message = $id > 0 ? "Güncelleme Başarılı" : "Kayıt Başarılı!!";

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

if ($_POST["action"] == "updateOrder") {
    $order = $_POST['order'];
    $order = json_decode($order, true);

    //Sıralamayı güncelleyin
    foreach ($order as $position => $id) {
        // ID'yi ayıklayın (örneğin, "item-1" -> 1)
        // $id = str_replace('item-', '', $id);

        $data = [
            "id" => $id,
            "header_order" => $position + 1
        ];
        $mission->saveWithAttr($data);
    }



    $res = [
        "status" => "success",
        "message" => "başarılı",
        "order" => $order

    ];

    echo json_encode($res);
}