<?php
require_once "Model/Company.php";
require_once "App/Helper/cities.php";

$cities = new Cities();



$companyObj = new Company();
$id = $_GET['id'] ?? 0;
$company = $companyObj->find($id);

$pageTitle = $id > 0 ? "Firma Güncelle" : "Yeni Firma";

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
                    <button type="button" class="btn btn-outline-secondary route-link" data-page="companies/list">
                        <i class="ti ti-list icon me-2"></i>
                        Listeye Dön
                    </button>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-primary" id="saveCompany">
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
                        <form action="" id="companyForm">
                            <div class="row d-flex">
                                <div class="col-md-4">
                                    <input type="text" name="id" class="form-control" value="<?php echo $id ?>">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="action" class="form-control" value="saveCompany">
                                </div>
                            </div>

                            <div class="row mb-3 mt-3">
                                <div class="col-md-2">
                                    <label for="company_name" class="form-label">Firma Adı</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="company_name" class="form-control" value="<?php echo $company->company_name ?? "" ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="form-label">Yetkilisi</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="yetkili" class="form-control" value="<?php echo $company->yetkili ?? "" ?>">
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="" class="form-label">Telefon</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="phone" class="form-control" value="<?php echo $company->phone ?? "" ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="form-label">Email</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="email" class="form-control" value="<?php echo $company->email ?? "" ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="" class="form-label">Vergi No</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="tax_number" class="form-control" value="<?php echo $company->tax_number ?? "" ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="form-label">Hesap No</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="account_number" class="form-control" value="<?php echo $company->account_number ?? "" ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="" class="form-label">Şehir</label>
                                </div>
                                <div class="col-md-4">
                                    <?php echo $cities->citySelect("firm_cities", $company->city ?? ''); ?>
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="form-label">İlçe</label>
                                </div>
                                <div class="col-md-4">
                                    <select name="firm_towns" id="firm_towns" class="form-control select2">
                                        <option value="">İlçe Seçiniz</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="" class="form-label">Açıklama</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="description" class="form-control" value="<?php echo $company->description ?? "" ?>">

                                </div>
                                <div class="col-md-2">
                                    <label for="" class="form-label">Adres</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="address" class="form-control" value="<?php echo $company->address ?? "" ?>">

                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>