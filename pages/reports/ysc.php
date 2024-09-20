<?php

use App\Helper\Helper;

require_once "App/Helper/helper.php";
require_once "Model/Report.php";
$report = new Reports();
$id = isset($_GET["id"]) ? $_GET["id"] : 0;

$report = $report->find($id);
?>
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Yangın Söndürme Tüpü Kontrol Raporu
                    </h2>
                </div>

                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-outline-secondary route-link" data-page="reports/list">
<i class="ti ti-list icon me-2"></i>
                        Listeye Dön
                    </button>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-primary" id="ysc_rapor_kaydet">
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
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tabs-home-3" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                    <i class="ti ti-home icon me-2"></i>
                                    GİRİŞ</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tabs-profile-3" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                    <i class="ti ti-table-shortcut icon me-2"></i>
                                    Ürün Bilgileri</a>
                            </li>
                        </ul>
                    </div>
                    <form action="" id="yscForm">
                        <input type="hidden" name="id" id="report_id" value="<?php echo $id ?>">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active show" id="tabs-home-3" role="tabpanel">
                                    <?php include_once "content/ysc/0-home.php" ?>
                                </div>
                                <div class="tab-pane" id="tabs-profile-3" role="tabpanel">
                                    <?php include_once "content/ysc/1-product-info.php" ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>