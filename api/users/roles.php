<?php

require_once "../../Database/require.php";
require_once "../../Model/RolesModel.php";


$roles = new Roles();


if ($_POST["action"] == "saveRoles") {
 
$id = $_POST["id"];
    try {
        $data = [
            "id" => $id,
            "firm_id" => $_SESSION["firm_id"],
            "roleName" => $_POST["role_name"],
            "roleDescription" => $_POST["role_description"],
        ];

        //yeni kayıt yapıldığı zaman son kaydedilen id yi döndürüyoruz.
        //Eğer güncelleme yapıldıysa id'yi döndürüyoruz.
        $lastInsertId = $roles->saveWithAttr($data) ?? $id;

        //Mesaj ve durum bilgisini döndürüyoruz.
        $status = "success";
        if ($id == 0) {
            $message = "Rol başarıyla kaydedildi.";
        } else {
            $message = "Rol başarıyla güncellendi.";
        }
    } catch (PDOException $e) {
        $status = "error";
        $message =  $e->getMessage();
    }

    //Geriye dönen sonucu array olarak döndürüyoruz.
    $res = [
        "status" => $status,
        "message" => $message,
        "lastid" => $lastInsertId ?? 0
    ];

    //Sonucu json formatına çevirip geri gönderiyoruz
    echo json_encode($res);
}

if ($_POST["action"] == "deleteRole") {
    $id = $_POST["id"];
    try {
        $roles->delete($id);
        $status = "success";
        $message = "Rol başarıyla silindi.";
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


if ($_POST["action"] == "copyRoles") {
    $id = $_POST["id"];
    $roles = $roles->getRolesByFirmExceptId($id);
    $res = [

        "roles" => $roles
    ];
    echo json_encode($res);
}

