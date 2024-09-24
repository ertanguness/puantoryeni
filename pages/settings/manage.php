<?php
require_once "Model/Cases.php";
$caseObj = new Cases();
$id = $_GET['id'] ?? 0;


$pageTitle =  "Ayarlar";

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
                    <div class="card-body">
                        <form action="" id="caseForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="id" class="form-control" value="">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="action" value="saveCase" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>