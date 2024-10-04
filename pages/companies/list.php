<?php

require_once "Model/Company.php";
require_once "App/Helper/helper.php";
require_once "App/Helper/cities.php";

use App\Helper\Helper;

$helper = new Helper();
$cities = new Cities();


$companyObj = new Company();
$companies = $companyObj->allWithUserId();

?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-auto">

                        <h2 class="card-title">Firma Listesi </h2>
                    </div>
                    <div class="col-auto ms-auto">
                    <a href="#" class="btn btn-icon me-2" data-tooltip="Excele Aktar">
                    <i class="ti ti-file-excel icon"></i>
                </a>
                        <a href="#" class="btn btn-primary route-link" data-page="companies/manage">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table card-table table-hover datatable ">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Firma Adı</th>
                                <th>Yetkili</th>
                                <th>Şehir</th>
                                <th>İlçe</th>
                                <th>Teleforn</th>
                                <th>Email</th>
                                <th>Adres</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($companies as $company) { ?>
                                <tr>
                                    <td><span class="text-secondary"><?php echo $company->id; ?></span></td>
                                    <td>
                                        <a href="invoice.html" class="text-reset" tabindex="-1">
                                            <?php echo Helper::short($company->company_name, 32) ?>
                                        </a>
                                    </td>

                                    <td>
                                        <?php echo $company->yetkili; ?>
                                    </td>
                                    <td>
                                        <?php echo $cities->getCityName($company->city); ?>
                                    </td>
                                    <td>
                                        <?php echo $cities->getTownName($company->town); ?>
                                    </td>
                                    <td>
                                        <?php echo $company->phone; ?>
                                    </td>
                                    <td>
                                        <?php echo $company->email; ?>
                                    </td>
                                    <td>
                                        <?php echo $company->address; ?>
                                    </td>


                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item route-link" data-page="companies/manage&id=<?php echo $company->id ?>" href="#">
                                                    <i class="ti ti-edit icon me-3"></i> Güncelle
                                                </a>
                                                <a class="dropdown-item delete-company" data-id="<?php echo $company->id ?>" href="#">
                                                    <i class="ti ti-trash icon me-3"></i> Sil
                                                </a>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script>



</script>