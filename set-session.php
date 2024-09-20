<?php
session_start();
$page = $_GET["p"];
$user_id =  $_SESSION['user']->id;
$user_role =  $_SESSION['user']->user_roles;
$_SESSION["firm_id"] = $_GET["firm_id"];

header("Location: index.php?p=$page");
