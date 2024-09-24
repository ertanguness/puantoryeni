<?php
require_once 'Model/Persons.php';
require_once 'Model/Bordro.php';
require_once 'App/Helper/company.php';
require_once 'App/Helper/helper.php';
require_once 'App/Helper/date.php';

use App\Helper\Helper;
use App\Helper\Date;

$person = new Persons();
$bordro = new Bordro();

$persons = $person->getByFirm($firm_id);
$company = new CompanyHelper();

// firma id'sini almak için $firm_id
?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Personel Listesi</h3>
                    <div class="d-flex col-auto ms-auto">
                  
                        <div class="dropdown me-2">
                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                                <i class="ti ti-list-details icon me-2"></i>
                                İşlemler</button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item add-wage-cut"
                                    data-tooltip="Personelleri yükleyin veya güncelleyin" data-tooltip-location="left"
                                    href="api/bordro/toexcel.php">
                                    <i class="ti ti-upload icon me-3"></i> Excelden Aktar
                                </a>
                                <a class="dropdown-item add-income" data-tooltip="Günlük Ücretleri güncelleyin"
                                    data-tooltip-location="left" href="#" data-bs-toggle="modal"
                                    data-bs-target="#income_modal">
                                    <i class="ti ti-user-dollar icon me-3"></i> Ücretleri Güncelle
                                </a>
                                <a class="dropdown-item add-income" data-tooltip="Personellere yapılan ödemeleri excelden yükleyin"
                                    data-tooltip-location="left" href="#" data-bs-toggle="modal"
                                    data-bs-target="#income_modal">
                                    <i class="ti ti-wallet icon me-3"></i> Ödeme Yükle
                                </a>

                            </div>
                        </div>
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
                                <th>Ücret Türü</th>
                                <th>Sigorta No</th>
                                <th>Telefon</th>
                                <th>Adres</th>
                                <th>Günlük/Aylık Ücretİ</th>
                                <th>Durumu</th>
                                <th style="width:1%">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php
                                foreach ($persons as $person):
                                    $wage_type = $person->wage_type == 1 ? 'Beyaz Yaka' : 'Mavi Yaka';
                                    $wage_type_color = $person->wage_type == 2 ? "style='color:blue'" : '';

                                    // Personel Beyaz Yaka ise
                                    if ($person->wage_type == 1) {
                                        // Ay ve yıl bilgilerini alalım
                                        $year = Date::getYear();
                                        $month = Date::getMonth(leadingZero: false);

                                        // Eylül 2024 Maaş şeklinde açıklama oluştur
                                        $description = Date::monthName($month) . ' ' . $year . ' Maaş';
                                        // Personelin aylık maaşı eklenmemişse
                                        if (!$bordro->isPersonMonthlyIncomeAdded($person->id, $month, $year)) {
                                            // Personelin aylık maaşını ekleyelim
                                            $bordro->addPersonMonthlyIncome($person->id, $month, $year, $person->daily_wages, $description);
                                        }
                                    }

                            ?>
                            <tr>
                                <td><?php echo $person->id; ?></td>
                                <td><?php echo $person->full_name; ?></td>
                                <td><?php echo $company->getcompanyName($person->company_id); ?></td>
                                <td <?php echo $wage_type_color; ?>><?php echo $wage_type; ?></td>
                                <td><?php echo $person->sigorta_no; ?></td>
                                <td><?php echo $person->phone; ?></td>
                                <td><?php echo $person->address; ?></td>
                                <td><?php echo Helper::formattedMoney($person->daily_wages ?? 0); ?></td>
                                <td><?php echo $person->state ?></td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle align-text-top"
                                            data-bs-toggle="dropdown">İşlem</button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item route-link"
                                                data-page="persons/manage&id=<?php echo $person->id ?>" href="#">
                                                <i class="ti ti-edit icon me-3"></i> Detay/Güncelle
                                            </a>
                                            <a class="dropdown-item"
                                                data-page="persons/manage&id=<?php echo $person->id ?>" href="#">
                                                <i class="ti ti-cash-register icon me-3"></i> Ödeme Yap
                                            </a>
                                            <a class="dropdown-item"
                                                data-page="persons/manage&id=<?php echo $person->id ?>" href="#">
                                                <i class="ti ti-cut icon me-3"></i> Kesinti Ekle
                                            </a>
                                            <a class="dropdown-item delete-person" data-id="<?php echo $person->id ?>"
                                                href="#">
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