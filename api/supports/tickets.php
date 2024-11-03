<?php

use App\Helper\Security;

require_once '../../Database/require.php';
require_once '../../Model/SupportsModel.php';
require_once '../../Model/SupportsMessagesModel.php';

$Supports = new SupportsModel();
$SupportsMessages = new SupportsMessagesModel();

if (isset($_POST['action']) && $_POST['action'] == 'saveSupportTicket') {
    $data = [
        'user_id' => $_SESSION['user']->id,
        'subject' => $_POST['subject'],
        'message' => $_POST['message'],
        'status' => 0,
        'program_name' => 'puantor'
    ];

    try {
        $lastInsertId = $Supports->saveWithAttr($data);

        // Destek talebi oluşturulduktan sonra destek mesajı oluşturuluyor
        $data = [
            'ticket_id' => Security::decrypt($lastInsertId),
            'message' => $_POST['message']
        ];
        $SupportsMessages->saveWithAttr($data);

        $status = "success";
        $message = "Destek talebiniz başarıyla oluşturuldu.";


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


