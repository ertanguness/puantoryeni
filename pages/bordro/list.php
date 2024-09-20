<?php
require_once "App/Helper/helper.php";
require_once "Model/Persons.php";
require_once "App/Helper/date.php";

use App\Helper\Helper;
use App\Helper\Date;

$personObj = new Persons();
$persons = $personObj->getByFirm($firm_id);

$year = isset($_POST["year"]) ? $_POST["year"] : date('Y');
$month = isset($_POST["months"]) ? $_POST["months"] : date('m');
$projet_id = isset($_POST["projects"]) ? $_POST["projects"] : 0;

//Ayın ilk gününü bulma
$firstDay = Date::firstDay($month, $year);

//Ayın son gününü bulma
$lastDay = Date::lastDay($month, $year);


?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bordro</h3>
                    <div class="col-auto ms-auto">
                        <a href="#" class="btn btn-primary route-link" data-page="defines/service-head/manage">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table card-table text-nowrap datatable">
                        <thead>
                            <tr>
                                <th style="width:1%">id</th>
                                <th>Personel Adı</th>
                                <th>Görevi</th>
                                <th>İşe Başlama Tarihi</th>
                                <th style="width:10%">Brüt Ücret</th>
                                <th style="width:10%">Kesinti</th>
                                <th style="width:10%">Net</th>
                                <th style="width:1%" class="text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($persons as $person) :
                            ?>
                                <tr>
                                    <td><?php echo $person->id; ?></td>
                                    <td><?php echo $person->full_name; ?></td>
                                    <td><?php echo $person->job; ?></td>
                                    <td><?php echo $person->job_start_date; ?></td>
                                    <td><?php echo $personObj->getPersonSalary($person->id, $firstDay, $lastDay); ?></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item route-link" data-tooltip="Avans,Ceza veya Bes gibi" data-tooltip-location="left" data-page="reports/ysc&id=<?php echo $person->id ?>" href="#">
                                                    <i class="ti ti-cut icon me-3"></i> Kesinti Ekle
                                                </a>
                                                <a class="dropdown-item route-link" data-tooltip="Prim,İkramiye veya Ödül gibi" data-tooltip-location="left" data-page="reports/ysc&id=<?php echo $person->id ?>" href="#">
                                                    <i class="ti ti-download icon me-3"></i> Gelir Ekle
                                                </a>
                                                <a class="dropdown-item route-link"  data-page="reports/ysc&id=<?php echo $person->id ?>" href="#">
                                                    <i class="ti ti-file-dollar icon me-3"></i> Bordro Gödter
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