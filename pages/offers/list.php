<?php
require_once "Model/Offer.php";
require_once "Model/Customer.php";
require_once "App/Helper/customer.php";

require_once "App/Helper/helper.php";

use App\Helper\Helper;

$teklif = new Offer();
$teklifler = $teklif->getTeklifler();

$customer = new Customer();


?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Teklifler</h3>
                </div>

                <div class="table-responsive">
                    <table class="table card-table text-nowrap datatable">
                        <thead>
                            <tr>
                                <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                                <th class="w-1">No. <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-sm icon-thick">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M6 15l6 -6l6 6"></path>
                                    </svg>
                                </th>
                                <th>Offer Number</th>
                                <th>Offer Date</th>
                                <th>Client</th>
                                <th>Tutar</th>
                                <th>Ödeme Vadesi</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($teklifler as $teklif) : ?>

                                <tr>
                                    <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>
                                    <td><span class="text-secondary"><?php echo $teklif->id ?></span></td>
                                    <td><a href="invoice.html" class="text-reset" tabindex="-1"><?php echo $teklif->offerNumber; ?></a></td>
                                    <td><span class="text-secondary"><?php echo $teklif->regdate ?></span></td>
                                    <td>
                                        <?php
                                        $musteri_adi = $customer->find($teklif->cid);
                                        echo $musteri_adi->company;
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo Helper::formattedMoney($teklif->buyTotal) ?>
                                    </td>
                                    <td>
                                        <?php echo $teklif->payment_period ?>
                                    </td>
                                    <td>
                                        <?php echo $teklif->statu ?>
                                    </td>

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">
                                                    Action
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    Another action
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