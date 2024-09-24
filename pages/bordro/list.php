<?php
require_once 'App/Helper/helper.php';
require_once 'Model/Persons.php';
require_once 'Model/Bordro.php';
require_once 'Model/Projects.php';
require_once 'App/Helper/date.php';
require_once 'App/Helper/projects.php';

use App\Helper\Date;
use App\Helper\Helper;

$projects = new Projects();
$projectHelper = new ProjectHelper();
$personObj = new Persons();
$bordro = new Bordro();

$year = isset($_POST['year']) ? $_POST['year'] : date('Y');
$month = isset($_POST['months']) ? $_POST['months'] : date('m');
$project_id = isset($_POST['projects']) ? $_POST['projects'] : 0;

if ($project_id == 0 || $project_id == '') {
    // Proje id boş ise Firma id'sine göre tüm personelleri getirir
    $persons = $personObj->getPersonIdByFirm($firm_id);
} else {
    // Proje id dolu ise projeye ait personelleri getirir
    $persons = $projects->getPersonFromProject($project_id);
    if (count($persons) > 0) {
        // 149,181 şeklinde gelen stringi diziye çevirir
        $persons = explode(',', $persons[0]->id);
        for ($i = 0; $i < count($persons); $i++) {
            // Dizi içindeki id'leri object yapar
            $persons[$i] = (object) ['id' => $persons[$i]];
        }
    }
}

// Ayın ilk gününü bulma (20240901) şeklinde döner
$firstDay = Date::firstDay($month, $year);

// Ayın son gününü bulma (20240930) şeklinde döner
$lastDay = Date::lastDay($month, $year);

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

            <div class="col-auto ms-auto mt-auto">
                <label for="year" class="form-label"></label>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                        <i class="ti ti-list-details icon me-2"></i>
                        İşlemler</button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item add-wage-cut" data-tooltip="Personelleri yükleyin veya güncelleyin"
                            data-tooltip-location="left" href="api/bordro/toexcel.php">
                            <i class="ti ti-upload icon me-3"></i> Excelden Aktar
                        </a>
                        <a class="dropdown-item add-income" data-tooltip="Günlük Ücretleri güncelleyin"
                            data-tooltip-location="left" href="#" data-bs-toggle="modal" data-bs-target="#income_modal">
                            <i class="ti ti-user-dollar icon me-3"></i> Ücretleri Güncelle
                        </a>

                    </div>
                </div>
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

                <style>

                </style>

                <div class="table-responsive">
                    <table class="table card-table text-nowrap datatable" id="bordroTable">
                        <thead>
                            <tr>
                                <th style="width:1%">id</th>
                                <th>Personel Adı</th>
                                <th>Ücret Türü</th>
                                <th>Görevi</th>
                                <th>İşe Başlama Tarihi</th>
                                <th style="width:10%" class="text-center">Brüt Ücret</th>
                                <th style="width:10%" class="text-center">Kesinti</th>
                                <th style="width:10%" class="text-center">Toplam Hakediş</th>
                                <th style="width:10%" class="text-center">Ödenen</th>
                                <th style="width:10%" class="text-center">Kalan</th>
                                <th style="width:1%" class="text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php
                            foreach ($persons as $item):
                                // Personel id'sine göre personel bilgilerini getirir
                                $person = $personObj->find($item->id);
                                // Personel id'sine göre personelin maaş ve kesinti bilgilerini getirir(Örnek: 20240901-20240930 arası)
                                $salary = $bordro->getPersonSalaryAndWageCut($person->id, $firstDay, $lastDay,$person->wage_type)->maas;
                                $gelir = $bordro->getPersonSalaryAndWageCut($person->id, $firstDay, $lastDay,$person->wage_type)->gelir;
                                $wage_cut = $bordro->getPersonSalaryAndWageCut($person->id, $firstDay, $lastDay,$person->wage_type)->kesinti;
                                $hakedis = $salary + $gelir - $wage_cut;
                                $odeme = $bordro->getPersonSalaryAndWageCut($person->id, $firstDay, $lastDay,$person->wage_type)->odeme;
                                $kalan = $hakedis - $odeme;
                                ?>
                                <tr>
                                    <td><?php echo $person->id; ?></td>
                                    <td><?php echo $person->full_name; ?></td>
                                    <td><?php echo $person->wage_type == 1 ? "Beyaz Yaka" : "Mavi Yaka"; ?></td>
                                    <td><?php echo $person->job; ?></td>
                                    <td><?php echo $person->job_start_date; ?></td>
                                    <td class="text-center"><?php echo Helper::formattedMoney(($salary + $gelir) ?? 0 ) ?></td>
                                    <td class="text-center"><?php echo Helper::formattedMoney($wage_cut ?? 0); ?></td>
                                    <td class="text-center">
                                        <?php echo Helper::formattedMoney(($hakedis) ?? 0); ?>
                                    </td>
                                    <td class="text-center"><?php echo Helper::formattedMoney($odeme ?? 0); ?></td>
                                    <td class="text-center"><?php echo Helper::formattedMoney($kalan ?? 0); ?></td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top"
                                                data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item add-payment" data-id="<?php echo $person->id ?>"
                                                    href="#" data-bs-toggle="modal" data-bs-target="#payment-modal">
                                                    <i class="ti ti-cash-register icon me-3"></i> Ödeme Yap
                                                </a>
                                                <a class="dropdown-item add-wage-cut" data-id="<?php echo $person->id ?>"
                                                    data-tooltip="Avans,Ceza veya Bes gibi" data-tooltip-location="left"
                                                    href="#" data-bs-toggle="modal" data-bs-target="#wage_cut_modal">
                                                    <i class="ti ti-cut icon me-3"></i> Kesinti Ekle
                                                </a>
                                                <a class="dropdown-item add-income" data-id="<?php echo $person->id ?>"
                                                    data-tooltip="Prim,İkramiye veya Ödül gibi" data-tooltip-location="left"
                                                    href="#" data-bs-toggle="modal" data-bs-target="#income_modal">
                                                    <i class="ti ti-download icon me-3"></i> Gelir Ekle
                                                </a>
                                                <a class="dropdown-item route-link"
                                                    data-page="bordro/pay-slip&id=<?php echo $person->id ?>" href="#">
                                                    <i class="ti ti-file-dollar icon me-3"></i> Bordro Göster
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

<?php include_once 'content/wage_cut-modal.php'; ?>
<?php include_once 'content/income-modal.php'; ?>
<?php include_once 'content/payment-modal.php'; ?>