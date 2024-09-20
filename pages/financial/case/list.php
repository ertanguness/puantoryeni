<?php
require_once "Model/Cases.php";
require_once "App/Helper/company.php";
require_once "App/Helper/helper.php";

use App\Helper\Helper;

$caseObj = new Cases();
$company = new CompanyHelper();

$cases = $caseObj->allWithFirmId($firm_id);



?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kasa Listesi</h3>
                    <div class="col-auto ms-auto">
                        <a href="#" class="btn btn-primary route-link" data-page="financial/case/manage">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table card-table text-nowrap datatable">
                        <thead>
                            <tr>
                                <th style="width:10%">id</th>
                                <th>Firması</th>
                                <th>Kasa Adı</th>
                                <th>Bankası</th>
                                <th>Şubesi</th>
                                <th>Başlangıç Bütçesi</th>
                                <th>Açıklama</th>
                                <th style="width:10%">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($cases as $case) :
                            ?>
                                <tr>
                                    <td><?php echo $case->id; ?></td>
                                    <td><?php echo $company->getFirmName($case->company_id); ?></td>
                                    <td><?php echo $case->case_name; ?></td>
                                    <td><?php echo $case->bank_name; ?></td>
                                    <td><?php echo $case->branch_name; ?></td>
                                    <td><?php echo Helper::formattedMoney($case->start_budget); ?></td>
                                    <td><?php echo $case->description; ?></td>

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item route-link" data-page="financial/transaction/list&id=<?php echo $case->id ?>" href="#">
                                                    <i class="ti ti-transfer icon me-3"></i> Kasa Hareketleri
                                                </a>
                                                <a class="dropdown-item route-link" data-page="financial/case/manage&id=<?php echo $case->id ?>" href="#">
                                                    <i class="ti ti-edit icon me-3"></i> Güncelle
                                                </a>
                                                <a class="dropdown-item delete-case" data-id="<?php echo $case->id ?>" href="#">
                                                    <i class="ti ti-trash icon me-3"></i> Sil
                                                </a>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>