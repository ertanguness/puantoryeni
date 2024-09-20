<?php
require_once "Model/Persons.php";



$id = $_GET['id'] ?? 0;
$personObj = new Persons();
$person = $personObj->find($id);

$pageTitle = $id > 0 ? "Personel Güncelle" : "Yeni Personel";

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
                    <button type="button" class="btn btn-outline-secondary route-link" data-page="persons/list">
                        <i class="ti ti-list icon me-2"></i>
                        Listeye Dön
                    </button>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-primary" id="savePerson">
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

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-home-3" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <i class="ti ti-home icon me-1"></i>
                                        Genel Bilgiler</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-payment-3" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <i class="ti ti-calculator icon me-1"></i>
                                        Ödeme Bilgileri</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-puantaj-3" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <i class="ti ti-calendar icon me-1"></i>
                                        Puantaj Bilgileri</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-leave-3" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <i class="ti ti-caravan icon me-1"></i>
                                        İzin Bilgileri</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-documents-3" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <i class="ti ti-checklist icon me-1"></i>
                                        Belgeler</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active show" id="tabs-home-3" role="tabpanel">
                                    <?php include_once "content/0-home.php" ?>
                                </div>
                                <div class="tab-pane" id="tabs-home-3" role="tabpanel">
                                    <?php include_once "content/1-payment-info.php" ?>
                                </div>
                                <div class="tab-pane" id="tabs-payment-3" role="tabpanel">
                                    <?php include_once "content/1-payment-info.php" ?>
                                </div>
                                <div class="tab-pane" id="tabs-puantaj-3" role="tabpanel">
                                    <?php include_once "content/1-payment-info.php" ?>
                                </div>
                                <div class="tab-pane" id="tabs-leave-3" role="tabpanel">
                                    <?php include_once "content/1-payment-info.php" ?>
                                </div>
                                <div class="tab-pane" id="tabs-documents-3" role="tabpanel">
                                    <?php include_once "content/1-payment-info.php" ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>