<?php
require_once "Database/require.php";
require_once "Model/User.php";

$user = new User();


function alertdanger($message)
{
    echo '<div class="alert alert-important alert-danger alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>Hata!</strong> ' . $message . '
  </div>';
}





?>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Puantor | Puantaj Takip Uygulaması</title>
    <meta name="msapplication-TileColor" content="#066fd1">
    <meta name="theme-color" content="#066fd1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">

    <!-- CSS files -->
    <link href="./dist/css/tabler.min.css?1726507346" rel="stylesheet">
    <link href="./dist/css/tabler-flags.min.css?1726507346" rel="stylesheet">
    <link href="./dist/css/tabler-payments.min.css?1726507346" rel="stylesheet">
    <link href="./dist/css/tabler-vendors.min.css?1726507346" rel="stylesheet">
    <link href="./dist/css/style.css?1726507346" rel="stylesheet">
    <link href="./dist/css/demo.min.css?1726507346" rel="stylesheet">
    <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
        font-feature-settings: "cv03", "cv04", "cv11";
    }
    </style>

</head>

<body class=" d-flex flex-column">
    <script>
    setTimeout(function() {
        $('.alert-danger').each(function() {
            $(this).fadeOut(500, function() {
                $(this).remove();
            });
        });
    }, 3000);
    </script>
    <script src="./dist/js/demo-theme.min.js?1726507346"></script>
    <div class="page page-center register">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark">
                    <img src="./static/logo.svg" height="36" alt="">
                </a>


                <?php


                if (isset($_POST["action"]) && $_POST["action"] == "saveUser") {
                    $full_name = $_POST['full_name'];
                    $company_name = $_POST['company_name'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    if (empty($full_name)) {
                        echo alertdanger("Ad Soyad alanı boş bırakılamaz.");
                    } elseif (empty($company_name)) {
                        echo alertdanger("Firma adı boş bırakılamaz.");
                    } elseif (empty($email)) {
                        echo alertdanger("Email alanı boş bırakılamaz.");
                    } elseif (empty($password)) {
                        echo alertdanger("Şifre alanı boş bırakılamaz.");
                    } else {

                        $data = [
                            'full_name' => $_POST['full_name'],
                            'email' => $_POST['email'],
                            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                        ];
                        try {
                            $user->saveWithAttr($data);
                            header("Location: register-success.php");
                        } catch (PDOException $exh) {
                            if($exh->errorInfo[1] == 1062){
                                echo alertdanger("Bu email adresi ile daha önce kayıt olunmuş.");
                            }

                        }

                    }
                }


                ?>




            </div>
            <form class="card card-md" action="#" method="post" autocomplete="off" novalidate="">
                <input type="hidden" name="action" class="form-control" value="saveUser">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Yeni Hesap Oluştur</h2>
                    <div class="mb-3">
                        <label class="form-label">Ad Soyad</label>
                        <input type="text" class="form-control" name="full_name" value="<?php echo $full_name ?? '' ?>"
                            placeholder="Adınız Soyadınız">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Firma Adı</label>
                        <input type="text" name="company_name" class="form-control"
                            value="<?php echo $company_name ?? '' ?>" placeholder="Firma adını giriniz!">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Adresi</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $email ?? '' ?>"
                            placeholder="Email adresinizi giriniz!">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Şifre</label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="password" class="form-control"
                                value="<?php echo $password ?? '' ?>" placeholder="Password" autocomplete="off">
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" data-bs-toggle="tooltip" aria-label="Show password"
                                    data-bs-original-title="Show password">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                        <path
                                            d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6">
                                        </path>
                                    </svg>
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input">
                            <span class="form-check-label"><a href="./terms-of-service.html"
                                    tabindex="-1">Şartlar ve  Koşulları</a> kabul ediyorum.</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Hesap Oluştur</button>
                    </div>
                </div>
            </form>
            <div class="text-center text-secondary mt-3">
                Zaten Hesabım var <a href="./sign-in.php" tabindex="-1">Giriş Yap</a>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->

    <script src="./dist/js/tabler.min.js?1692870487" defer></script>
    <script src="./dist/js/demo.min.js?1692870487" defer></script>
    <script src="./dist/js/jquery.3.7.1.min.js"></script>

</body>

</html>