<?php
require_once "Model/Cases.php";
require_once "App/Helper/company.php";

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
                    <div class="card-body">
                        <form action="" id="caseForm">
                            <div class="row d-none">
                                <div class="col-md-4">
                                    <input type="text" name="id" class="form-control" value="<?php echo $case->id ?? 0 ?>">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="action" value="saveCase" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="case_name" class="form-label">Firması</label>
                                </div>
                                <div class="col-md-4">
                                    <?php echo $company->myCompanySelect("firm_company", $case->company_id ?? ''); ?>
                                </div>
                                <div class="col-md-2">
                                    <label for="case_name" class="form-label">Kasa Adı</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="case_name" id="case_name" class="form-control" value="<?php echo $case->case_name ?? '' ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="case_name" class="form-label">Bankası</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="bank_name" class="form-control" value="<?php echo $case->bank_name ?? '' ?>">

                                </div>
                                <div class="col-md-2">
                                    <label for="case_name" class="form-label">Şubesi</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="branch_name" class="form-control" value="<?php echo $case->branch_name ?? '' ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="case_name" class="form-label">Başlangıç Bütçesi</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="start_budget" class="form-control" value="<?php echo $case->start_budget ?? '' ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="case_name" class="form-label">Açıklama</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="description" class="form-control" value="<?php echo $case->description ?? '' ?>">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>