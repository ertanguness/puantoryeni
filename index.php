<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

ob_start();// Çıktı tamponlamasını başlatır
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {

    $returnUrl = urlencode($_SERVER["REQUEST_URI"]);
    if (!isset($_GET["p"])) {
        $returnUrl = urlencode("/index.php?p=home");
    }


    header("Location: sign-in.php?returnUrl={$returnUrl}");
    exit();
}

require_once "Database/db.php";
require_once "Model/Menus.php";
require_once "Model/UserModel.php";
require_once 'Model/Auths.php';

$menus = new Menus();
$userObj = new UserModel();
$perm = new Auths();



//kullanıcının kayıtlı mail adresi birden fazla ise seçili firmadaki bilgileri ile işlem yapılır
$email = $_SESSION['user']->email;
$firm_id = $_SESSION['firm_id'];

$user = $userObj->getUserByEmailAndFirm($email, $firm_id);
$_SESSION['user'] = $user;


$active_page = isset($_GET["p"]) ? $_GET["p"] : "";
$menu_name = $menus->getMenusByLink($active_page);


?>



<?php include_once "inc/head.php" ?>

<body class=" layout-fluid">
    <script src="./dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page">

        <!-- Preloader -->
        <div class="preloader">
            <div class="preloader-icon"></div>
        </div>

        <!-- Navbar -->
        <?php include "inc/menu.php" ?>
        <!-- Sidebar -->

        <!-- Navbar -->
        <?php include "inc/topbar.php" ?>
        <!-- Sidebar -->

        <!-- Sidebar -->
        <div class="page-wrapper">

            <?php
            $page = isset($_GET["p"]) ? $_GET["p"] : "home";
            ; ?>

            <?php
            if (isset($_GET["p"]) && file_exists("pages/{$page}.php")) {

                include "pages/{$page}.php";

            } else if (!file_exists("pages/{$page}.php")) {

                include "pages/404.php";
            } else
                (
                    include "pages/home.php"
                );
            ?>
            <?php include "inc/footer.php" ?>
        </div>
    </div>

    <?php include "inc/vendor-scripts.php" ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var preloader = document.querySelector('.preloader');
            fadeOut(preloader, 300);
        });
    </script>

</body>

</html>