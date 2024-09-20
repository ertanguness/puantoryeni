<?php
require_once "Model/CaseTransactions.php";
require_once "App/Helper/helper.php";
require_once "App/Helper/date.php";
require_once "App/Helper/financial.php";


use App\Helper\Helper;
use App\Helper\Date;


$ct = new CaseTransactions();
$transactions = $ct->all();
$financial = new Financial();

?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gelir-Gider Hareketleri</h3>
                    <div class="col-auto ms-auto">
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-general">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>

                    </div>
                </div>


                <div class="table-responsive">
                    <table id="transactionTable" class="table card-table text-nowrap datatable">
                        <thead>
                            <tr>
                                <th style="width:5%">İd</th>
                                <th style="width:5%">Tarih</th>
                                <th style="width:5%">İşlem Türü</th>
                                <th style="width:5%">Alt Tür</th>
                                <th>Kasası</th>
                                <th>Tutar</th>
                                <th>Açıklama</th>
                                <th style="width:10%" class="text-end">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($transactions as $transaction) :
                            ?>
                                <tr>
                                    <td><?php echo $transaction->id ?></td>
                                    <td><?php echo Date::dmY($transaction->date) ?></td>
                                    <td class="text-center"><?php echo $transaction->type_id ?></td>
                                    <td class="text-center"><?php echo $transaction->sub_type ?></td>
                                    <td><?php echo $financial->getCaseName($transaction->case_id) ?></td>
                                    <td><?php echo Helper::formattedMoney($transaction->amount, $transaction->amount_money ?? 1) ?></td>
                                    <td><?php echo $transaction->description ?></td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item route-link" data-page="financial/transaction/manage&id=<?php echo $transaction->id ?>" href="#">
                                                    <i class="ti ti-edit icon me-3"></i> Güncelle
                                                </a>
                                                <a class="dropdown-item delete-transaction" data-id="<?php echo $transaction->id ?>" href="#">
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

<?php include_once "modals/general-modal.php"; ?>