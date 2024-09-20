<?php include_once "configs/require.php" ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Kayıt Ol | YoneApp Apartman Yönetim Sistemi</title>
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

<?php

if ($_POST) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $terms = isset($_POST['termsandpoliciy']) ? $_POST['termsandpoliciy'] : null;
}; ?>

<script>
  setTimeout(function() {
    $('.alert-danger').each(function() {
      $(this).fadeOut(500, function() {
        $(this).remove();
      });
    });
  }, 3000);
</script>


<body class=" d-flex flex-column">
  <script src="./dist/js/demo-theme.min.js?1692870487"></script>
  <div class="page page-center">
    <div class="container container-tight py-4">
      <div class="text-center mb-4">
        <a href="." class="navbar-brand navbar-brand-autodark">
          <img src="./static/logo.svg" width="110" height="32" alt="Tabler" class="navbar-brand-image">
        </a>
      </div>
      <?php


      if ($_POST && isset($_POST['submitForm'])) {
        if (empty($name)) {
          alertdanger("Ad Soyad alanı boş bırakılamaz.");
        } else if (empty($email)) {
          alertdanger("Email alanı boş bırakılamaz.");
        } else if (empty($password)) {
          alertdanger("Şifre alanı boş bırakılamaz.");
        } else if (empty($terms)) {
          alertdanger("Şartları kabul etmediniz.");
        } else {

          $sql = $db->prepare("INSERT INTO users SET full_name = ?, email = ?, password = ?");
          $insert = $sql->execute([$name, $email, md5($password)]);


          header("Location: sign-in.php");
        }
      }
      ?>
      <form class="card card-md" action="#" method="POST" id="signupForm" autocomplete="off" novalidate>
        <div class="card-body">
          <h2 class="card-title text-center mb-4">Yeni Hesap Oluştur</h2>
          <div class="mb-3">
            <label class="form-label">Ad Soyad</label>
            <input type="text" class="form-control" name="name" value="<?php echo $name ?? ''; ?>" placeholder="Adınız ve Soyadınız">
          </div>

          <div class="mb-3">
            <label class="form-label">Email Adresi</label>
            <input type="email" class="form-control" name="email" value="<?php echo $email ?? ''; ?>" autocomplete="on" placeholder="Email adresiniz">
          </div>

          <div class="row mb-3 d-flex">
            <label class="form-label">Şifre</label>
            <div class="input-group input-group-flat">
              <input type="password" class="form-control" name="password" placeholder="Şifreniz" autocomplete="off">
              <span class="input-group-text">
                <a href="#" class="link-secondary" title="Göster" data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                  </svg>
                </a>
              </span>

            </div>
          </div>
          <div class="mb-3">
            <label class="form-check">
              <input type="checkbox" class="form-check-input" name="termsandpoliciy" />
              <span class="form-check-label"><a href="./terms-of-service.html" tabindex="-1">Şartlar ve Politikaları </a> kabul ediyorum.</span>
            </label>
          </div>
          <div class="form-footer">
            <button type="submit" name="submitForm" class="btn btn-primary w-100">Hesap Oluştur</button>
          </div>
        </div>
      </form>
      <div class="text-center text-secondary mt-3">
        Zaten Hesabım var? <a href="./sign-in.php" tabindex="-1">Oturum Aç</a>
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