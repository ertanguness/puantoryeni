<?php 
require_once "Database/require.php";
require_once "App/Helper/security.php";

use App\Helper\Security;

$enc_token = (Security::encrypt(time()+3600));
$dec_token = (Security::decrypt($enc_token));

echo "Encrypted Token: $enc_token <br>";
echo "Decrypted Token:" . $dec_token . "-" . time()+3600 .  "<br>";


