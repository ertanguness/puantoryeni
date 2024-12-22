<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

//bu dosyanın bulunduğu dizini belirtir
define("ROOT", __DIR__);
date_default_timezone_set('Europe/Istanbul'); // Change to your timezone



ob_start();// Çıktı tamponlamasını başlatır
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {

    $returnUrl = urlencode($_SERVER["REQUEST_URI"]);
    if (!isset($_GET["p"])) {
        $returnUrl = urlencode("/index.php?p=home");
    }
    header("Location: sign-in.php?returnUrl={$returnUrl}");
    exit();
}
// CSRF token oluşturma
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once "Database/db.php";
require_once "Model/Menus.php";
require_once "Model/UserModel.php";
require_once 'Model/Auths.php';
require_once 'App/Helper/security.php';

use App\Helper\Security;


$menus = new Menus();
$User = new UserModel();

$perm = new Auths();//Sayfalarda yetki kontrolü yapmak için kullanılır

$user = $User->find($_SESSION['user']->id) ?? null;

if (!$user) {

    // $log_id = $_SESSION["log_id"];
    // $Users->logoutLog($log_id);
    header("Location: sign-in.php");
    exit();
}



$_SESSION["user"] = $user;

if ($_SESSION["user"]->parent_id != 0) {
    //kullanıcının kayıtlı mail adresi birden fazla ise seçili firmadaki bilgileri ile işlem yapılır
    $email = $_SESSION['user']->email ?? null;
    $firm_id = $_SESSION['firm_id'];
    $user = $User->getUserByEmailAndFirm($email, $firm_id);
    $_SESSION['user'] = $user;
}

// if ($user->status == 0) {
//     header("Location: sign-in.php");
//     exit();
// }

//Eğer kullanıcı demo kullanıcısı ise
if ($user->user_type == 1) {
    //Kullanıcının kayıt olduğu tarihten itibaren geçen süreyi 15'ten çıkar , tam sayı halinde, mutlak değer olarak döndürür
    $diff = 15 - date_diff(date_create($user->created_at), date_create(date("Y-m-d")))->format("%a");
    //süre bittiyse kullanıcıya bildirim göster ve çıkış yap
    if ($diff <= 0) {
        header("Location: sign-in.php");
        exit();
    }
}


$active_page = isset($_GET["p"]) ? $_GET["p"] : "";
$menu_name = $menus->getMenusByLink($active_page);


?>



<?php include_once "inc/head.php" ?>
<style>

</style>

<body class="layout-fluid">

    <div class="page">


        <div class="fab-menu text-center">
            <button class="main-fab" onclick="toggleFabMenu()">
                <span id="main-icon" class="icon-hamburger">&#9776;</span>
                <span id="close-icon" class="icon-close">&#10006;</span>
            </button>
            <div class="fab-options" id="fab-options">
                <button class="fab-item route-link" data-page="supports/tickets" style="bottom: 70px;">
                    <span>Teknik Destek</span>
                    <i class="ti ti-headset text-white"></i>
                </button>
                <button class="fab-item" onclick="goWhatsApp()" style="bottom: 130px;">
                    <span>WhatsApp</span>
                    <i class="ti ti-brand-whatsapp text-white"></i>
                </button>
                <button class="fab-item route-link" data-page="feedback/send" style="bottom: 190px;">
                    <span>Görüş & Öneri</span>
                    <i class="ti ti-brand-feedly text-white"></i>
                </button>
            </div>
        </div>





        <!-- Diğer HTML içeriğiniz -->

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
            <div class="container-xl">
                <!-- Eğer kullanıcı demo kullanıcısı ise uyarı göster -->
                <?php if ($user->user_type == 1) { ?>
                    <div class="alert alert-warning alert-dismissible bg-white mb-0 mt-3" role="alert">
                        <div class="d-flex">
                            <div>
                                <i class="ti ti-alert-triangle icon me-3"></i>
                            </div>
                            <div>
                                Deneme sürenizin bitmesine kalan süre <?php echo $diff; ?> gün, süreniz bitmeden önce
                                paketinizi <a href="index.php?p=settings/manage&tab=edit-account"
                                    class="text-warning"><u>güncelleyin.</u></a>
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                <?php } ?>
            </div>
            <?php
            $page = isset($_GET["p"]) ? $_GET["p"] : "home";
            // echo "user token" . $user->session_token;
            // echo "session token : ".$_SESSION['csrf_token'];
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
        // document.addEventListener("DOMContentLoaded", function () {
        //  var preloader = document.querySelector('.preloader');
        //  fadeOut(preloader, 300);
        // });
        $(document).ready(function () {
            var preloader = $('.preloader');
            preloader.fadeOut(500);
        });
    </script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/src/service-worker.js').then((registration) => {
                    //console.log('Service Worker registered with scope:', registration.scope);
                }).catch((error) => {
                    console.error('Service Worker registration failed:', error);
                });
            });
        }

    </script>
    <script>
        $(document).ready(function () {
            $('.collapse-button').on('click', function () {
                var $button = $(this);
                var $navbar = $('#navbar');
                var $pageWrapper = $('.page-wrapper');
                $button.toggleClass('active');
                $navbar.toggleClass('collapsed');
                $pageWrapper.toggleClass('page-collapse');
            });
        });



    </script>

</body>

</html>