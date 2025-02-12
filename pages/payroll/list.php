<?php
require_once 'App/Helper/helper.php';
require_once 'Model/Persons.php';
require_once 'Model/Bordro.php';
require_once 'Model/Projects.php';
require_once 'App/Helper/date.php';
require_once 'App/Helper/projects.php';
require_once "App/Helper/financial.php";
require_once "App/Helper/security.php";
require_once "Model/Cases.php";

use App\Helper\Security;
use App\Helper\Date;
use App\Helper\Helper;


$Cases = new Cases();
$projects = new Projects();
$projectHelper = new ProjectHelper();
$personObj = new Persons();
$bordro = new Bordro();
$FinancialHelper = new Financial();

$year = isset($_POST['year']) ? $_POST['year'] : date('Y');
$month = isset($_POST['months']) ? $_POST['months'] : date('m');
$last_day = Date::Ymd(Date::lastDay($month, $year));
$project_id = isset($_POST['projects']) ? $_POST['projects'] : 0;
if ($project_id == 0 || $project_id == '') {
    // Proje id boş ise Firma id'sine göre tüm personelleri getirir
    $persons = $personObj->getPersonIdByFirmCurrentMonth($firm_id, $last_day);
} else {
    // Proje id dolu ise projeye ait personelleri getirir
    $persons = $projects->getPersonIdByFromProjectCurrentMonth($project_id, $last_day);
}
// Ayın ilk gününü bulma (20240901) şeklinde döner
$firstDay = Date::firstDay($month, $year);
// Set the default timezone to your local timezone

// Ayın son gününü bulma (20240930) şeklinde döner
$lastDay = Date::lastDay($month, $year);

$case_id = $Cases->getDefaultCaseIdByFirm();

?>
<div class="container-xl mt-3">
    <form action="" method="post" id="bordroInfoForm">
        <div class="row">
            <div class="col-3">
                <label for="projects" class="form-label">Proje:</label>
                <?php echo $projectHelper->getProjectSelect('projects', $project_id); ?>
            </div>
            <div class="col-3">
                <label for="months" class="form-label">Ay:</label>
                <?php echo Date::getMonthsSelect('months', $month); ?>
            </div>
            <div class="col-3">
                <label for="year" class="form-label">Yıl:</label>
                <?php echo Date::getYearsSelect('year', $year); ?>
            </div>

            <div class="col-auto ms-auto mt-auto d-flex">
                <?php
                if ($Auths->hasPermission('payroll_export_excel')) { ?>
                    <label for=""></label>
                    <a href="#" class="btn btn-icon me-2" id="export_excel" data-tooltip="Excele Aktar">
                        <i class="ti ti-file-excel icon"></i>
                    </a>
                <?php } ?>



                <label for="" class="form-label"></label>

                <div class="dropdown">
                    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                        <i class="ti ti-list-details icon me-2"></i>
                        İşlemler</button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <?php if ($Auths->hasPermission('upload_payment_permission')) { ?>
                            <a class="dropdown-item add-income route-link" href="#"
                                data-tooltip="Personellere yapılan ödemeleri excelden yükleyin" data-tooltip-location="left"
                                data-page="payroll/xls/payment-load-from-xls">
                                <i class="ti ti-table-import icon me-3"></i> Ödeme Yükle
                            </a>
                        <?php } ?>
                        <?php if ($Auths->hasPermission('update_fees_permission')) { ?>
                            <a class="dropdown-item add-income" data-tooltip="Günlük Ücretleri güncelleyin"
                                data-tooltip-location="left" href="#" data-bs-toggle="modal" data-bs-target="#income_modal">
                                <i class="ti ti-user-dollar icon me-3"></i> Ücretleri Güncelle
                            </a>
                        <?php } ?>

                        <?php if ($Auths->hasPermission('payroll_export_excel')) { ?>
                            <a class="dropdown-item add-income"
                                data-tooltip="Personellere yapılacak ödeme listesini indirin" data-tooltip-location="left"
                                href="pages/payroll/xls/bank-list-for-payments.php">
                                <i class="ti ti-checklist icon me-3"></i> Banka Listesi İndir
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <?php if ($Auths->hasPermission('payroll_calculate')) { ?>
                    <a class="btn btn-primary ms-2" href="#" id="payroll_calculate">
                        <i class="ti ti-calculator icon me-3"></i> Hesapla
                    </a>
                <?php } ?>


            </div>
        </div>
    </form>
</div>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bordro</h3>
                    <div class="col-auto ms-auto">
                        <!-- <a href="#" class="btn btn-primary route-link" data-page="defines/service-head/manage">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a> -->
                    </div>
                </div>



                <div class="table-responsive">
                    <table class="table card-table table-responsive table-hover text-nowrap datatable" id="bordroTable">
                        <thead>
                            <tr>
                                <th style="width:1%">Sıra</th>
                                <th>Personel Adı</th>
                                <th>Ücret Türü</th>
                                <th>Görevi</th>
                                <th>İşe Başlama Tarihi</th>
                                <th style="width:10%" class="text-center">Brüt Ücret</th>
                                <th style="width:10%" class="text-center">Ödenen/Kesinti</th>
                                <!-- <th style="width:10%" class="text-center">Devreden</th> -->
                                <th style="width:10%" class="text-center">Ödenecek</th>

                                <th style="width:1%" class="text-center no-export">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php
                            $i = 1;
                            foreach ($persons as $item):


                                // Personel id'sine göre personel bilgilerini getirir
                                $person = $personObj->find($item->id);
                                $person_id = Security::encrypt($person->id);
                                $id = Security::encrypt($person->id);

                                //personelin görevden ayrılma tarihi firstday' büyükse personeli getirme
                                if ($person->job_end_date != null) {
                                    if ($person->job_end_date >= $firstDay) {
                                        continue;
                                    }
                                }

                                // Personel Beyaz Yaka ise
                                if (isset($_POST["action"]) && $_POST["action"] == 'payroll_calculate') {

                                    if ($person->wage_type == 1) {
                                        // Eylül 2024 Maaş şeklinde açıklama oluştur
                                        $description = Date::monthName($month) . ' ' . $year . ' Maaş';
                                        // Personelin aylık maaşı eklenmemişse
                                        // Personelin işe başlama tarihi o ay içindeyse
                            
                                        //veya personelin işe başlama tarihi o aydan önceyse
                                        //Eğer ayın ilk günü bugünden küçükse
                                        if ($firstDay <= Date::Ymd(date('Y-m-d'))) {
                                            if (
                                                Date::isBetween($person->job_start_date, $firstDay, $lastDay) ||
                                                Date::isBefore($person->job_start_date, $firstDay)
                                            ) {


                                                $montly_income = $bordro->isPersonMonthlyIncomeAdded($person->id, $month, $year)->id ?? 0;

                                                // Personelin aylık maaşı ekle mi diye kontrol et
                                                if ($montly_income == 0) {
                                                    // Personelin aylık maaşını ekleyelim
                                                    $bordro->addPersonMonthlyIncome($person->id, $month, $year, $person->daily_wages, $description);
                                                } else {
                                                    //echo $montly_income;
                                                    // Personelin aylık maaşını güncelleyelim,işe başlaama tarihinde değişiklik olduğu zaman maaş güncellenir
                                                    $bordro->updatePersonMonthlyIncome($person->id, $montly_income, $month, $year);
                                                }
                                            }
                                        }
                                    }
                                }

                                // Personel id'sine göre personelin maaş ve kesinti bilgilerini getirir(Örnek: 20240901-20240930 arası)
                                $gelir = $bordro->getPersonSalaryAndWageCut($person->id, $firstDay, $lastDay)->gelir;
                                $odeme = $bordro->getPersonSalaryAndWageCut($person->id, $firstDay, $lastDay)->odeme;
                                $kalan = $gelir - $odeme;

                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i; ?></td>
                                    <td> <a href="#" data-tooltip="Detay/Güncelle"
                                            data-page="persons/manage&id=<?php echo $id ?>"
                                            class="nav-item route-link"><?php echo $person->full_name; ?></a></td>
                                    <td><?php echo $person->wage_type == 1 ? 'Beyaz Yaka' : 'Mavi Yaka'; ?></td>
                                    <td><?php echo $person->job; ?></td>
                                    <td><?php echo $person->job_start_date; ?></td>

                                    <!-- Gelir -->
                                    <td class="text-end ">
                                        <?php echo Helper::formattedMoney(($gelir) ?? 0) ?>
                                        <i class="ti ti-download icon text-green"></i>

                                    </td>


                                    <td class="text-end">
                                        <?php echo Helper::formattedMoney($odeme ?? 0); ?>
                                        <i class="ti ti-cash-register icon color-green"></i>

                                    </td>



                                    <!-- Bakiye rengini belirle ve göster -->
                                    <td class="text-end <?php echo Helper::balanceColor($kalan) ?>">
                                        <!-- //Bakiyesini yazdır -->
                                        <?php echo Helper::formattedMoney($kalan ?? 0); ?>
                                        <i class="ti ti-credit-card-pay icon"></i>
                                    </td>


                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top"
                                                data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <?php if ($Auths->hasPermission('make_staff_payment')) { ?>
                                                    <a class="dropdown-item add-payment" data-id="<?php echo $id ?>" href="#"
                                                        data-bs-toggle="modal" data-bs-target="#payment-modal">
                                                        <i class="ti ti-cash-register icon me-3"></i> Ödeme Yap
                                                    </a>
                                                <?php } ?>

                                                <?php if ($Auths->hasPermission("income_expense_add_update")) {
                                                    ; ?>
                                                    <a class="dropdown-item add-wage-cut" data-id="<?php echo $id ?>"
                                                        data-tooltip="Avans,Ceza veya Bes gibi" data-tooltip-location="left"
                                                        href="#">
                                                        <i class="ti ti-cut icon me-3"></i> Kesinti Ekle
                                                    </a>

                                                    <a class="dropdown-item add-income" data-id="<?php echo $id ?>"
                                                        data-tooltip="Prim,İkramiye veya Ödül gibi" data-tooltip-location="left"
                                                        href="#" data-bs-toggle="modal" data-bs-target="#income_modal">
                                                        <i class="ti ti-download icon me-3"></i> Gelir Ekle
                                                    </a>
                                                <?php } ?>

                                                <?php
                                                $link = "index.php?p=payroll/pay-slip&id=" . $id . "&month=" . Security::encrypt($month) . "&year=" . Security::encrypt($year);
                                                ?>

                                                <a class="dropdown-item" target="_blank"
                                                    href="index.php?p=payroll/pay-slip&id=<?php echo $link ?>">
                                                    <i class="ti ti-file-dollar icon me-3"></i> Bordro Göster
                                                </a>

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

<?php include_once 'content/wage_cut-modal.php'; ?>
<?php include_once 'content/income-modal.php'; ?>
<?php include_once 'content/payment-modal.php'; ?>
<?php include_once 'content/payment-load-modal.php'; ?>