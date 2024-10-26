<?php

use App\Helper\Security;

require_once "Model/Projects.php";
require_once "App/Helper/company.php";
require_once "App/Helper/cities.php";

$companyHelper = new CompanyHelper();
$cityHelper = new Cities();

$perm->checkAuthorize("project_add_update");

$projectObj = new Projects();

//id decrypt edilir
$id = isset($_GET['id']) ? Security::decrypt($_GET['id']) : 0;

//yeni kayıt için id 0 olmalı
$new_id=isset($_GET["id"]) ? ($_GET['id']) : 0;

//Eğer url'den id yazılmışsa veya id boş ise projeler sayfasına gider
if($id == null && isset($_GET['id'])) {
    header("Location: /index.php?p=projects/list");
    exit;
}

//id'ye göre kayıt getirilir
$project = $projectObj->find($id);

$pageTitle = $id > 0 ? "Proje Detay/Güncelle" : "Yeni Proje";

?>
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <?php echo $pageTitle; ?>
                    </h2>
                </div>

                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-outline-secondary route-link" data-page="projects/list">
                        <i class="ti ti-list icon me-2"></i>
                        Listeye Dön
                    </button>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-primary" id="saveProject">
                        <i class="ti ti-device-floppy icon me-2"></i>
                        Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="col-md-12">

                <form action="" id="projectForm">
                    <!-- HIDDEN ROW -->
                    <div class="row d-none ">
                        <div class="col-md-4">
                            <input type="text" name="id" id="id" class="form-control" value="<?php echo $new_id ?>">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="action" value="saveProject" class="form-control">
                        </div>
                    </div>
                    <!-- HIDDEN ROW -->

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-home-3" class="nav-link active" data-bs-toggle="tab"
                                            aria-selected="true"
                                            role="tab">
                                            <i class="ti ti-home icon me-1"></i>
                                            Genel Bilgiler
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-profile-3" class="nav-link" data-bs-toggle="tab"
                                            aria-selected="false" tabindex="-1"
                                            role="tab">
                                            <i class="ti ti-clipboard-text icon me-1"></i>
                                            Diğer Bilgiler
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-payment-3" class="nav-link" data-bs-toggle="tab"
                                            aria-selected="false" tabindex="-1"
                                            role="tab">
                                            <i class="ti ti-cash-register icon me-1"></i>
                                            Hakediş/Ödeme Bilgileri
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-puantaj-3" class="nav-link" data-bs-toggle="tab"
                                            aria-selected="false" tabindex="-1"
                                            role="tab">
                                            <i class="ti ti-calendar-month icon me-1"></i>
                                            Çalışma/Puantaj Bilgileri
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active show" id="tabs-home-3" role="tabpanel">
                                        <?php include_once "content/0-home.php" ?>

                                    </div>
                                    <div class="tab-pane" id="tabs-profile-3" role="tabpanel">
                                        <?php include_once "content/1-other-info.php" ?>
                                    </div>
                                    <div class="tab-pane" id="tabs-payment-3" role="tabpanel">
                                        <?php include_once "content/2-payment-info.php" ?>

                                    </div>
                                    <div class="tab-pane" id="tabs-puantaj-3" role="tabpanel">
                                        <?php include_once "content/3-works-puantaj-info.php" ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'modals/payment-modal.php' ?>
<?php include_once 'modals/progress-payment-modal.php' ?>