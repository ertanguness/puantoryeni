<?php
require_once 'Model/Persons.php';
require_once 'Model/Bordro.php';
require_once 'App/Helper/company.php';
require_once 'App/Helper/helper.php';
require_once 'App/Helper/date.php';


//Yetki Kontrolü yapılır
$perm->checkAuthorize("personnel_page");

use App\Helper\Helper;
use App\Helper\Date;
use App\Helper\Security;

$person = new Persons();
$bordro = new Bordro();

$persons = $person->getPersonsByFirm($firm_id);
$company = new CompanyHelper();





?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Personel Listesi</h3>
                    <div class="d-flex col-auto ms-auto">
                        <a href="/pages/persons/to-pdf.php" class="btn btn-icon me-2" data-page=""
                            data-tooltip="Pdf'e Aktar">
                            <i class="ti ti-file-type-pdf icon"></i>
                        </a>
                        <a href="#" id="export_excel" class="btn btn-icon me-2" data-page=""
                            data-tooltip="Excele Aktar">
                            <i class="ti ti-file-excel icon"></i>
                        </a>
                        <div class="dropdown me-2">
                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                                <i class="ti ti-list-details icon me-2"></i>
                                İşlemler</button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item route-link"
                                    data-tooltip="Personelleri Excel dosyasından yükleyin" data-tooltip-location="left"
                                    href="#" data-page="persons/xls/person-load">
                                    <i class="ti ti-upload icon me-3"></i> Excelden Yükle
                                </a>
                                <a class="dropdown-item add-income" data-tooltip="Günlük Ücretleri güncelleyin"
                                    data-tooltip-location="left" href="#" data-bs-toggle="modal"
                                    data-bs-target="#income_modal">
                                    <i class="ti ti-user-dollar icon me-3"></i> Ücretleri Güncelle
                                </a>


                            </div>
                        </div>
                        <a href="#" class="btn btn-primary route-link" data-page="persons/manage">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>

                    </div>

                </div>


                <div class="table-responsive">
                    <table class="table card-table table-hover text-nowrap datatable" id="persons">
                        <thead>
                            <tr>
                                <th style="width:5%">Sıra</th>
                                <th>Adı Soyadı</th>
                                <th>Firma Adı</th>
                                <th>Ücret Türü</th>
                                <th>Sigorta No</th>
                                <th>Telefon</th>
                                <th>Adres</th>
                                <th>Günlük/Aylık Ücretİ</th>
                                <th>Durumu</th>
                                <th>Güncel Bakiyesi</th>
                                <th style="width:7%" class="no-export">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php
                            $i = 1;
                            foreach ($persons as $person):
                                $wage_type = $person->wage_type == 1 ? 'Beyaz Yaka' : 'Mavi Yaka';
                                $wage_type_color = $person->wage_type == 2 ? "style='color:blue'" : '';
                                $balance = $bordro->getBalance($person->id);
                                $color = Helper::balanceColor($balance);
                                $id = Security::encrypt($person->id);

                                ?>
                                <?php if ($person->firm_id == $_SESSION["firm_id"]) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td> <a href="#" data-tooltip="Detay/Güncelle"
                                                data-page="persons/manage&id=<?php echo $id ?>"
                                                class="nav-item route-link"><?php echo $person->full_name; ?></a></td>
                                        <td><?php echo $company->getcompanyName($person->company_id ) ?? 0; ?></td>
                                        <td <?php echo $wage_type_color; ?>><?php echo $wage_type; ?></td>
                                        <td><?php echo $person->sigorta_no; ?></td>
                                        <td><?php echo $person->phone; ?></td>
                                        <td><?php echo $person->address; ?></td>
                                        <td><?php echo Helper::formattedMoney($person->daily_wages ?? 0); ?></td>
                                        <td><?php echo $person->state ?></td>
                                        <td class="<?php echo $color ?>"><?php echo Helper::formattedMoney($balance) ?></td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle align-text-top"
                                                    data-bs-toggle="dropdown">İşlem</button>
                                                <div class="dropdown-menu dropdown-menu-end">


                                                    <a class="dropdown-item route-link"
                                                        data-page="persons/manage&id=<?php echo $id ?>" href="#">
                                                        <i class="ti ti-edit icon me-3"></i> Detay/Güncelle
                                                    </a>

                                                    <a class="dropdown-item delete-person" data-id="<?php echo $id ?>"
                                                        href="#">
                                                        <i class="ti ti-trash icon me-3"></i> Sil
                                                    </a>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                <?php } ?>
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