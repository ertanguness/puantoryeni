<?php
require_once "Model/Persons.php";
require_once "App/Helper/helper.php";
require_once "App/Helper/company.php";

use App\Helper\Helper;

$person = new Persons();

$persons = $person->getByFirm($firm_id);
$company = new CompanyHelper();

//firma id'sini almak için $firm_id
?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Personel Listesi</h3>
                    <div class="col-auto ms-auto">
                        <a href="#" class="btn btn-primary route-link" data-page="persons/manage">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table card-table table-hover text-nowrap datatable">
                        <thead>
                            <tr>
                                <th style="width:1%">ID</th>
                                <th>Adı Soyadı</th>
                                <th>Firma Adı</th>
                                <th>Proje Adı</th>
                                <th>Sigorta No</th>
                                <th>Telefon</th>
                                <th>Adres</th>
                                <th>Günlük Ücreti</th>
                                <th>Durumu</th>
                                <th style="width:1%">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($persons as $person) :
                            ?>
                                <tr>
                                    <td><?php echo $person->id; ?></td>
                                    <td><?php echo $person->full_name; ?></td>
                                    <td><?php echo $company->getcompanyName($person->company_id); ?></td>
                                    <td><?php echo $person->project_id; ?></td>
                                    <td><?php echo $person->sigorta_no; ?></td>
                                    <td><?php echo $person->phone; ?></td>
                                    <td><?php echo $person->address; ?></td>
                                    <td><?php echo Helper::formattedMoney($person->daily_wages ?? 0); ?></td>
                                    <td><?php echo $person->state ?></td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item route-link" data-page="persons/manage&id=<?php echo $person->id ?>" href="#">
                                                    <i class="ti ti-edit icon me-3"></i> Güncelle
                                                </a>
                                                <a class="dropdown-item delete-person" data-id="<?php echo $person->id?>" href="#">
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