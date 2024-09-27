<script src="./dist/js/jquery.3.7.1.min.js"></script>
<?php
$page = isset($_GET['p']) ? $_GET['p'] : '';
if (
    $page == 'companies/list' ||
    $page == 'offers/list' ||
    $page == 'reports/list' ||
    $page == 'users/list' ||
    $page == 'users/roles/list' ||
    $page == 'products/list' ||
    $page == 'defines/service-head/list' ||
    $page == 'persons/list' ||
    $page == 'persons/manage' ||
    $page == 'mycompany/list' ||
    $page == 'financial/case/list' ||
    $page == 'financial/transactions/list' ||
    $page == 'financial/transactions/manage' ||
    $page == 'projects/list' || $page == 'projects/manage' ||
    $page == 'projects/add-person' ||
    $page == 'puantaj/list' ||
    $page == 'bordro/list'
) {
    echo '<script src="./dist/libs/datatable/datatables.min.js"></script>';
}

if ($page == 'offers/add') {
    echo '<script src="./src/offer.js" defer ></script>';
}
if ($page == 'reports/ysc') {
    echo '<script src="./src/report-ysc.js" ></script>';
}

// Kullanıcı ekleme ve düzenleme sayfası
if ($page == 'users/list' || $page == 'users/manage') {
    echo '<script src="./src/users/users.js"></script>';
}

// Kullanıcı rolü ekleme ve düzenleme sayfası
if ($page == 'users/roles/list' || $page == 'users/roles/manage') {
    echo '<script src="./src/users/roles.js"></script>';
}
// Ürün ekleme ve düzenleme sayfası
if ($page == 'products/list' || $page == 'products/manage') {
    echo '<script src="./src/product.js"></script>';
}

// Servis Konusu ekleme ve düzenleme sayfası
if ($page == 'defines/service-head/list' || $page == 'defines/service-head/manage') {
    echo '<script src="./src/defines/service-head.js"></script>';
}
// Personel Liste, ekleme ve düzenleme sayfası
if ($page == 'persons/list' || $page == 'persons/manage') {
    echo '<script src="./src/persons/persons.js"></script>';
}
// Personel diğer bilgileri ekleme ve düzenleme sayfası
if ($page == 'persons/manage') {
    echo '<script src="./src/persons/payment.js"></script>';
    echo '<script src="./src/persons/wages.js"></script>';
    echo '<script src="./src/persons/income.js"></script>';
    echo '<script src="./src/persons/wage-cut.js"></script>';
}

// Servis Konusu ekleme ve düzenleme sayfası
if ($page == 'mycompany/list' || $page == 'mycompany/manage') {
    echo '<script src="./src/companies/mycompanies.js"></script>';
}

if ($page == 'companies/list' || $page == 'companies/manage') {
    echo '<script src="./src/companies/companies.js"></script>';
}

// Kasa (kasa ekleme ve düzenleme sayfası)
if ($page == 'financial/case/list' || $page == 'financial/case/manage') {
    echo '<script src="./src/financial/case.js"></script>';
}
// Kasa İşlemleri(kasa ekleme ve düzenleme sayfası)
if ($page == 'financial/transactions/list') {
    echo '<script src="./src/financial/transactions.js"></script>';
}   
// Proje Ekleme,güncelleme ve listeleme sayfası
if ($page == 'projects/list' || $page == 'projects/manage' || $page == 'projects/add-person') {
    echo '<script src="./src/project/projects.js"></script>';
    echo '<script src="./src/project/progress-payment.js"></script>';
    echo '<script src="./src/project/payment.js"></script>';
}
// Puantaj Ekleme,güncelleme ve listeleme sayfası
if ($page == 'puantaj/list') {
    echo '<script src="./src/puantaj/puantaj.js"></script>';
}
// Bordro sayfası
if ($page == 'bordro/list') {
    echo '<script src="./src/bordro/bordro.js"></script>';
    echo '<script src="./src/bordro/payment.js"></script>';
}
?>







<?php
if ($page == 'home') {
    echo '<script src="./dist/libs/apexcharts/dist/apexcharts.min.js" defer></script>';
    echo '<script src="./dist/libs/jsvectormap/dist/js/jsvectormap.min.js" defer></script>';
    echo '<script src="./dist/libs/jsvectormap/dist/maps/world.js" defer></script>';
    echo '<script src="./dist/libs/jsvectormap/dist/maps/world-merc.js" defer></script>';
    echo '<script src="./src/charts.js" defer></script>';
}
?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/tr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="./dist/libs/select2/js/select2.min.js?1724846371"></script>
<!-- Tabler Core -->
<script src="./dist/js/tabler.min.js?1692870487"></script>
<script src="./dist/js/demo.min.js?1692870487"></script>



<script src="./src/app.js" defer??></script>