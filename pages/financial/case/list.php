<?php
require_once "Model/Cases.php";
require_once "App/Helper/company.php";
require_once "App/Helper/helper.php";


use App\Helper\Helper;
use App\Helper\Security;

$caseObj = new Cases();
$company = new CompanyHelper();

$Auths->checkFirmReturn();
$perm->checkAuthorize("cash_register_list");


$cases = $caseObj->allCaseWithFirmId();

?>
<div class="container-xl">
    <div class="alert alert-info bg-white alert-dismissible mt-3" role="alert">
        <div class="d-flex">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon alert-icon">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                    <path d="M12 9h.01"></path>
                    <path d="M11 12h1v4h1"></path>
                </svg>
            </div>
            <div>
                <h4 class="alert-title">Kasa Listesi!</h4>
                <div class="text-secondary">Firmanız için tanımlı kasaları buradan yönetebilirsiniz.Gelir gider
                    işlemleriniz içini varsayılan kasayı seçmeyi unutmayın!</div>
            </div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
    <div class="row row-deck row-cards">


        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kasa Listesi</h3>
                    <div class="col-auto ms-auto">
                        <?php
                        $link = $Auths->Authorize("cash_register_add_update") ? "financial/case/manage" : "authorize";
                        ?>
                        <a href="#" class="btn btn-primary route-link" data-page="<?php echo $link; ?>">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table card-table text-nowrap datatable row-selected">
                        <thead>
                            <tr>
                                <th style="width:7%">id</th>
                                <th>Firması</th>
                                <th>Kasa Adı</th>
                                <th>Bankası</th>
                                <th>Şubesi</th>
                                <th>Para Birimi</th>
                                <th>Varsayılan mı?</th>
                                <th>Başlangıç Bütçesi</th>
                                <th>Güncel Bakiye</th>
                                <th>Açıklama</th>
                                <th style="width:7%">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            //Kullanıcı firma id ve session firm_id eşleşiyorsa;
                            if ($Auths->checkFirm()): ?>

                                <?php
                                $i = 1;
                                foreach ($cases as $case):
                                    $id = Security::encrypt($case->id)
                                        ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td><?php echo $company->getFirmName($case->company_id ?? ''); ?></td>
                                        <td> <a class="nav-item route-link" data-tooltip="Detay/Güncelle"
                                                data-page="financial/case/manage&id=<?php echo $id ?>" href="#">
                                                <?php echo $case->case_name; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $case->bank_name; ?></td>
                                        <td><?php echo $case->branch_name; ?></td>
                                        <td><?php echo Helper::money($case->case_money_unit); ?></td>
                                        <td class="text-center"><?php

                                        if ($case->isDefault == 1) {
                                            echo '<i class="ti ti-check icon color-green"></i>';
                                        }
                                        ?></td>
                                        <td><?php echo Helper::formattedMoney($case->start_budget); ?></td>
                                        <td>1</td>
                                        <td><?php echo $case->description; ?></td>

                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle align-text-top"
                                                    data-bs-toggle="dropdown">İşlem</button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item route-link"
                                                        data-page="financial/transaction/list&id=<?php echo $id ?>" href="#">
                                                        <i class="ti ti-transfer icon me-3"></i> Kasa Hareketleri
                                                    </a>

                                                    <?php
                                                    //Ekleme ve güncelleme yetkisi varsa
                                                    if ($Auths->hasPermission("cash_register_add_update")) { ?>
                                                        <a class="dropdown-item route-link"
                                                            data-page="financial/case/manage&id=<?php echo $id ?>" href="#">
                                                            <i class="ti ti-edit icon me-3"></i> Güncelle/Detay
                                                        </a>
                                                    <?php } ?>

                                                    <a class="dropdown-item default-case" data-id="<?php echo $id ?>" href="#">
                                                        <i class="ti ti-checks icon me-3"></i> Varsayılan Yap
                                                    </a>
                                                    <?php
                                                    //Ekleme ve güncelleme yetkisi varsa
                                                    if ($Auths->hasPermission("cash_delete")) { ?>
                                                        <a class="dropdown-item delete-case" data-id="<?php echo $id ?>" href="#">
                                                            <i class="ti ti-trash icon me-3"></i> Sil
                                                        <?php } ?>
                                                    </a>
                                                </div>
                                            </div>


                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>