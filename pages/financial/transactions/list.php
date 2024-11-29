<?php
require_once "Model/Cases.php";
require_once "Model/CaseTransactions.php";
require_once "App/Helper/helper.php";
require_once "App/Helper/date.php";
require_once "App/Helper/financial.php";
require_once "App/Helper/security.php";



use App\Helper\Helper;
use App\Helper\Date;
use App\Helper\Security;




if (isset($_POST['case_id'])) {
    $case_id = $_POST["case_id"] != 0 ? Security::decrypt($_POST['case_id']) : 0;
} else {
    $case_id = 0;
}


//Kullanıcının firmasını kontro eder
$Auths->checkFirmReturn();

//Sayfa başlarında eklenecek alanlar
$perm->checkAuthorize("income_expense_operations");


$cases = new Cases();
$ct = new CaseTransactions();
$transactions = $case_id != 0 ? $ct->allTransactionByCase(case_id: $case_id) : $ct->allTransactionByFirm($_SESSION["firm_id"]);

$financial = new Financial();
$financialHelper = new Financial();

?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gelir-Gider Hareketleri</h3>
                    <input type="hidden" class="form-control" id="transaction_id" name="transaction_id" value="0">
                    <div class="col-auto ms-auto d-flex align-items-center">
                        <div class="me-2" style="min-width:300px;">
                            <form action="#" method="post" id="caseForm">


                                <?php echo $financialHelper->getCasesSelectByUser("firm_cases",$case_id); ?>
                            </form>
                        </div>

                        <div class="dropdown me-2">
                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                                <i class="ti ti-file-power icon me-2"></i>
                                Hızlı İşlemler</button>
                            <div class="dropdown-menu dropdown-menu-end">

                                <!-- Projeden Ödeme Alma yetkisi varsa -->
                                <?php if ($Auths->hasPermission('receive_payment_from_project')) { ?>
                                    <a class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#get_payment_from_project-modal" data-tooltip="Projeden ödeme al"
                                        href="#">
                                        <i class="ti ti-buildings icon me-3"></i> Projeden Ödeme Al
                                    </a>
                                <?php } ?>

                                <!-- Personel ödeme yetkisi varsa -->
                                <?php if ($Auths->hasPermission('make_staff_payment')) { ?>
                                    <a class="dropdown-item add-income" data-tooltip="Personele yapılan ödemeleri ekleyin"
                                        href="#" data-bs-toggle="modal" data-bs-target="#pay_to_person-modal">
                                        <i class="ti ti-user-dollar icon me-3"></i> Personel Ödemesi Yap
                                    </a>
                                <?php } ?>

                                <!-- Yüklenici ödeme yetkisi varsa -->
                                <?php if ($Auths->hasPermission('make_company_payment')) { ?>
                                    <a class="dropdown-item add-income"
                                        data-tooltip="Yüklenici Firmaya yapılan ödemeleri ekleyin" href="#"
                                        data-bs-toggle="modal" data-bs-target="#pay_to_company-modal">
                                        <i class="ti ti-home-stats icon me-3"></i> Firma Ödemesi Yap
                                    </a>
                                <?php } ?>

                                <!-- Alınan projeye masraf ekleme yetkisi varsa -->
                                <?php if ($Auths->hasPermission('add_project_expense')) { ?>
                                    <a class="dropdown-item add-income"
                                        data-tooltip="Alınan projeye yapılan masrafları ekleyin" href="#"
                                        data-bs-toggle="modal" data-bs-target="#add_expense_received_project-modal">
                                        <i class="ti ti-building-estate icon me-3"></i> Alınan Proje Masraf Ekle
                                    </a>
                                <?php } ?>


                            </div>
                        </div>

                        <?php if ($Auths->hasPermission('income_expense_add_update')) { ?>
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#general-modal">
                                <i class="ti ti-plus icon me-2"></i> Yeni
                            </a>
                        <?php } ?>


                    </div>
                </div>

                <div class="table-responsive">
                    <table id="transactionTable" class="table card-table text-nowrap table-hover datatable">
                        <thead>
                            <tr>
                                <th style="width:7%" class="text-center">Sıra</th>
                                <th style="width:5%">Tarih</th>
                                <th style="width:5%">İşlem Türü</th>
                                <th style="width:5%">Alt Tür</th>
                                <th>Kasası</th>
                                <th style="width:12%">Tutar</th>
                                <th>Açıklama</th>
                                <th style="width:7%" class="text-end">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php
                            $i = 1;
                            foreach ($transactions as $transaction):
                                $id = Security::encrypt($transaction->id);
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i ?></td>
                                    <td><?php echo Date::dmY($transaction->date) ?></td>
                                    <td class="text-center"><?php echo Helper::getTransactionType($transaction->type_id ?? 0) ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $financialHelper->getTransactionType($transaction->sub_type ?? 0) ?>
                                    </td>
                                    <td><?php echo $cases->find($transaction->case_id)->case_name ?></td>
                                    <td class="text-end"><?php echo Helper::formattedMoney($transaction->amount, $transaction->amount_money ?? 1) ?>
                                    </td>
                                    <td><?php echo $transaction->description ?></td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top"
                                                data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <!-- Gelir Gider Güncelleme yetkisi kontrol edilir -->
                                                <?php if ($Auths->hasPermission('income_expense_add_update')): ?>
                                                    <a class="dropdown-item edit-transactions" href="#" data-id="<?php echo $id ?>">
                                                        <i class="ti ti-edit icon me-3"></i> Güncelle
                                                    </a>
                                                <?php endif ?>

                                                <?php if ($Auths->hasPermission("delete_income_expense")): ?>
                                                    <a class="dropdown-item delete-transaction" data-id="<?php echo $id ?>"
                                                        href="#">
                                                        <i class="ti ti-trash icon me-3"></i> Sil
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            <?php
                                $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include_once "modals/general-modal.php"; ?>
<?php include_once "modals/get_payment_from_project-modal.php"; ?>
<?php include_once "modals/pay_to_person-modal.php"; ?>
<?php include_once "modals/pay_to_company-modal.php"; ?>
<?php include_once "modals/add_expense_received_project-modal.php"; ?>