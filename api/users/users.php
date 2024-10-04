<?php

require_once "../../Database/require.php";
require_once "../../Model/User.php";
require_once "../../App/Helper/date.php";


use App\Helper\Date;


$user = new User();

if ($_POST["action"] == "userSave") {
    $id = $_POST["id"];
    $lastInsertId = 0;

    try {
        $data = [
            "id" => $id,
            "firm_id" => $_SESSION["firm_id"],
            "full_name" => $_POST["full_name"],
            "email" => $_POST["email"],
            "password" => md5($_POST["password"]),
            "phone" => $_POST["phone"],
            "user_roles" => $_POST["user_roles"],
            "job" => $_POST["job"],

        ];

        $lastInsertId = $user->saveUser($data) ?? $id;
        $status = "success";
        if ($id == 0) {
            $message = "Kullanıcı başarıyla kaydedildi.";
        } else {
            $message = "Kullanıcı başarıyla güncellendi.";
        }
    } catch (PDOException $e) {
        $status = "error";
        if ($e->errorInfo[1] == 1062) {
            $message = 'Bu e-posta adresi zaten kayıtlı.';
        } else {
            $message =  $e->getMessage();
        }
    }
    $res = [
        "status" => $status,
        "message" => $message,
        "lastid" => $lastInsertId
    ];
    echo json_encode($res);
}


if ($_POST["action"] == "deleteUser") {
    $id = $_POST["id"];
    try {
        $user->delete($id);
        $status = "success";
        $message = "Kullanıcı başarıyla silindi.";
    } catch (PDOException $e) {
        $status = "error";
        $message =  $e->getMessage();
    }
    $res = [
        "status" => $status,
        "message" => $message
    ];
    echo json_encode($res);
}
