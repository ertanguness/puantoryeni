<?php
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once ROOT . "/Database/require.php";
require_once ROOT . "/Model/UserModel.php";
require_once ROOT . "/App/Helper/date.php";


use App\Helper\Date;


$User = new UserModel();

if ($_POST["action"] == "userSave") {
    $id = $_SESSION["user"]->id;
    $lastInsertId = 0;
    
    try {
        //Email adresi ile kayıtlı ana kullanıcı varsa kayıt yapılmaz
             $data = [
            "id" => $id,
            "full_name" => $_POST["full_name"],
            "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
            "phone" => $_POST["phone"],
            "user_roles" => $_POST["user_roles"],
            "job" => $_POST["job"],


        ];

        $lastInsertId = $User->saveWithAttr($data) ?? $id;
        $status = "success";
        if ($id == 0) {
            $message = "Profil Bilgileriniz başarıyla kaydedildi.";
        } else {
            $message = "Profil Bilgileriniz başarıyla güncellendi.";
        }
    } catch (PDOException $e) {
        $status = "error";
        if ($e->errorInfo[1] == 1062) {
            $message = 'Bu e-posta adresi zaten kayıtlı.';
        } else {
            $message = $e->getMessage();
        }
    }
    $res = [
        "status" => $status,
        "message" => $message,
        "lastid" => $lastInsertId
    ];
    echo json_encode($res);
}