<?php
require_once "Model/Cases.php";
require_once "App/Helper/company.php";

$Auths->checkAuthorize("cash_register_add_update");

$company = new CompanyHelper();
$caseObj = new Cases();
$id = $_GET['id'] ?? 0;
$case = $caseObj->find($id);

$pageTitle = $id > 0 ? "Kasa Güncelle" : "Yeni Kasa";

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
                    <button type="button" class="btn btn-outline-secondary route-link" data-page="financial/case/list">
                        <i class="ti ti-list icon me-2"></i>
                        Listeye Dön
                    </button>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-primary" id="saveCase">
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
                <div class="card">

                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-home-3" class="nav-link active" data-bs-toggle="tab"
                                        aria-selected="true" role="tab">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <i class="ti ti-home icon me-1"></i>
                                        Genel Bilgiler
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-payment-3" class="nav-link" data-bs-toggle="tab"
                                        aria-selected="false" tabindex="-1" role="tab">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <i class="ti ti-calculator icon me-1"></i>
                                        Kasa Hareketleri
                                    </a>
                                </li>


                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active show" id="tabs-home-3" role="tabpanel">
                                    <?php include_once 'content/0-home.php' ?>
                                </div>
                                <div class="tab-pane" id="tabs-payment-3" role="tabpanel">
                                    <?php include_once 'content/1-payment-info.php' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>