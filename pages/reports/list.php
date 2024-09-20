<?php
require_once "Model/Report.php";
require_once "App/Helper/helper.php";
require_once "App/Helper/company.php";

use App\Helper\Helper;

$report = new Reports();
$reports = $report->all();
$customer = new CompanyHelper();


?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rapor Listesi</h3>
                </div>

                <div class="table-responsive">
                    <table class="table card-table text-nowrap datatable">
                        <thead>
                            <tr>
                                <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                                <th class="w-1">ID </th>
                                <th class="w-1">Rapor No </th>
                                <th>Firma</th>
                                <th>Rapor Türü</th>
                                <th>İş Emri No</th>
                                <th>Kontrol Tarihi</th>
                                <th>Geçerlilik Tarihi</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($reports as $report) :
                                $companyName = $customer->getCompanyName($report->customer_id);
                            ?>
                                <tr>
                                    <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>

                                    <td><?php echo $report->id ?></span></td>
                                    <td><?php echo $report->report_number ?></span></td>
                                    <td data-bs-toggle="tooltip" title="<?php echo $companyName; ?>"><?php echo Helper::short($companyName, 44) ?></span></td>
                                    <td>
                                        <?php echo $report->report_type ?></span>
                                    </td>
                                    <td>
                                        <?php echo $report->isemrino ?></span>
                                    </td>
                                    <td>
                                        <?php echo $report->control_date ?></span>
                                    </td>
                                    <td>
                                        <?php echo $report->next_control_date ?></span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item route-link" data-page="reports/ysc&id=<?php echo $report->id ?>" href="#">
                                                    <i class="ti ti-edit icon me-3"></i> Güncelle
                                                </a>
                                                <a class="dropdown-item" href="#">
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