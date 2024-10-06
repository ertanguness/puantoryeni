<?php

require_once 'configs/require.php';
require_once 'Model/User.php';

$userObj = new User();
// if ($_POST && isset($_POST['submitForm'])) {
//   $email = $_POST['email'];
//   $password = MD5($_POST['password']);
//   echo $password;
// };

?>



<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Oturum Aç | Puantor - Puantaj Takip Sistemi</title>
  <!-- CSS files -->
  <link href="./dist/css/tabler.min.css?1692870487" rel="stylesheet" />
  <link href="./dist/css/demo.min.css?1692870487" rel="stylesheet" />
  <link href="./dist/css/style.css" rel="stylesheet" />
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


<script>
  setTimeout(function () {
    $('.alert-danger').each(function () {
      $(this).fadeOut(500, function () {
        $(this).remove();
      });
    });
  }, 3000);
</script>

<body class=" d-flex flex-column">
  <script src="./dist/js/demo-theme.min.js?1692870487"></script>
  <div class="page page-center">
    <div class="container container-normal py-4">
      <div class="row align-items-center g-4">
        <div class="col-lg">
          <div class="container-tight">
            <div class="text-center mb-4">
              <a href="." class="navbar-brand navbar-brand-autodark">
                <img src="./static/logo-ai.svg" height="120" alt=""></a>
            </div>
            <?php
              if ($_POST && isset($_POST['submitForm'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                // Email adresi boş ise
                if (empty($email)) {
                  echo alertdanger('Email adresi boş bırakılamaz');
                } elseif (empty($password)) {
                  echo alertdanger('Şifre boş bırakılamaz');
                } else {
                  $user = $userObj->getUserByEmail($email);
                  // Kullanıcı bulunamadıysa
                  if (!$user) {
                    echo alertdanger('Kullanıcı bulunamadı');
                    // Kullanıcı aktif değilse
                  } else if (isset($user) && $user->status == 0) {
                    echo alertdanger('Kullanıcı henüz aktif değil');
                  } else {
                    $verified = password_verify($password, $user->password);

                    if ($verified) {
                
                      $_SESSION['user'] = $user;
                      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                      $_SESSION['full_name'] = $user->full_name;
                      $_SESSION['user_role'] = $user->user_roles;

                      // returnUrl parametresini kontrol edin ve varsayılan değeri ayarlayın
                      //$returnUrl = isset($_GET['returnUrl']) && !empty($_GET['returnUrl']) ? $_GET['returnUrl'] : '';
                       //header("Location: company-list.php?returnUrl={$returnUrl}");
                      //header("Location:company-list.php");
                      header("Location: company-list.php");
                      exit();
                      
                    } else {
                      echo alertdanger('Hatalı şifre veya email adresi');
                    }
                  }
                }
              }
            ?>
            <div class="card card-md">
              <div class="card-body">
                <h2 class="h2 text-center mb-4">Oturum Aç</h2>
                <form method="POST" action="#" autocomplete="off">

                  <div class="mb-3">
                    <label class="form-label">Email Adresi</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $email ?? '' ?>"
                      placeholder="Email adresinizi girin" autocomplete="off">
                  </div>
                  <div class="mb-2">
                    <label class="form-label">
                      Şifre
                      <span class="form-label-description">
                        <a href="./forgot-password.php">Şifremi Unuttum</a>
                      </span>
                    </label>
                    <div class="input-group input-group-flat">
                      <input type="password" class="form-control" name="password" placeholder="Şifrenizi giriniz"
                        autocomplete="off">
                      <span class="input-group-text">
                        <a href="#" class="link-secondary" title="Göster" data-bs-toggle="tooltip">
                          <!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path
                              d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                          </svg>
                        </a>
                      </span>
                    </div>
                  </div>
                  <div class="mb-2">
                    <label class="form-check">
                      <input type="checkbox" class="form-check-input" />
                      <span class="form-check-label">Beni Hatırla</span>
                    </label>
                  </div>
                  <div class="form-footer">
                    <button type="submit" name="submitForm" class="btn btn-primary w-100">Giriş
                      Yap</button>
                  </div>
                </form>
              </div>


            </div>
            <div class="text-center text-secondary mt-3">
              Henüz hesabınız yok mu? <a href="./register.php" tabindex="-1">Kayıt Ol</a>
            </div>
          </div>
        </div>
        <!-- <div class="col-lg d-none d-lg-block">
          
          <img src="./static/illustrations/undraw_sign_in_e6hj.svg" height="300" class="d-block mx-auto" alt="">
        </div> -->
      </div>
    </div>
  </div>
  <!-- Libs JS -->
  <!-- Tabler Core -->
  <script src="./dist/js/tabler.min.js?1692870487" defer></script>
  <script src="./dist/js/demo.min.js?1692870487" defer></script>
</body>

</html>