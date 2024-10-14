<?php
require_once 'App/Helper/helper.php';
require_once 'App/Helper/date.php';
require_once 'App/Helper/projects.php';
require_once 'App/Helper/puantaj.php';
require_once 'Model/Persons.php';
require_once 'Model/Puantaj.php';

use App\Helper\Date;
use App\Helper\Helper;

$puantajHelper = new puantajHelper();
$projectHelper = new ProjectHelper();
$personObj = new Persons();
$projects = new Projects();

$puantajObj = new Puantaj();

$year = isset($_POST['year']) ? $_POST['year'] : date('Y');
$month = isset($_POST['months']) ? $_POST['months'] : date('m');
$last_day = Date::Ymd(Date::lastDay($month, $year));
$project_id = isset($_POST['projects']) ? $_POST['projects'] : 0;


if ($project_id == 0 || $project_id == '') {
    // Proje id boş ise Firma id'sine göre tüm mavi yakalı, işe başlama tarihi o ayın son gününden önce olan personelleri getirir
    $persons = $personObj->getPersonIdByFirmBlueCollarCurrentMonth($firm_id, $last_day);
} else {
    // Proje id dolu ise projeye ait, işe başlama tarihi o ayın son gününden önce olan mavi yakalı personelleri getirir
    $persons = $projects->getPersonIdByFromProjectCurrentMonth($project_id, $last_day);

}

// Ayın son gününü bulma
$days = Date::daysInMonth($month, $year);
// Tarihleri oluşturma
$dates = Date::generateDates($year, $month, $days);

?>
<style>
    .gun {
        width: 35px;
        min-width: 35px;
        background-color: white;
        text-align: center;
        cursor: pointer;
        font-weight: 600;

    }

    .gunadi {
        width: 40px !important;
        max-width: 40px !important;

    }

    table.dataTable.table-sm>thead>tr>th:not(.sorting_disabled) {
        padding: 7px !important;
    }


    .dataTables_wrapper .dataTables_filter {
        display: none;
    }

    .table {
        padding-bottom: 15px !important;
        overflow: auto !important;

    }

    .table tbody tr td {
        max-height: 45px !important;
        height: 45px !important;
        padding: 4px !important;
        vertical-align: middle !important;
    }

    .table tr td,
    .table th {
        border: 1px solid #ddd !important;
    }



    .gun.clicked {
        background-color: #FFED00 !important;
        cursor: pointer;
    }

    .unclicked {
        background-color: white;
    }

    th:hover {
        cursor: pointer;
    }

    th.ld {
        min-width: 100px;
    }

    th.vertical {
        height: 100px;
        width: 40px;
        min-width: 40px;
        max-width: 40px;
        vertical-align: bottom;
        padding: 0;
        line-height: 1.5;
        position: relative;
    }



    th.vertical span {
        writing-mode: vertical-lr;
        font-size: 12px;
        transform: rotate(180deg);
        font-weight: 800;

    }

    .hover-menu {

        /* width: 100%; */
        overflow-y: auto;
        height: 400px;
    }

    .hover-menu ul li {
        padding: 10px;
        margin: 5px;
        border: none !important;

    }

    .hover-menu ul li:hover {
        background-color: #eee;
        border-radius: 6px;
        cursor: pointer;


    }

    .hover-menu .nav-item {
        padding: 7px !important;
        margin: 0px !important;

    }

    .card-body .hover-menu {
        padding: 5px !important;

    }

    .hover-menu ul li:active {
        background-color: #ccc;
        transition: background-color 0.4s ease;
        /* Geçiş efekti */
    }

    .grid {
        gap: 2px;
    }

    .noselect {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }

    .nav-pills .nav-link {
        white-space: nowrap;
    }

    .no-wrap {
        white-space: nowrap;
    }

    .avatar {

        color: white;
        opacity: 1;
        content: attr(data-initials);
        font-weight: bold;
        border-radius: 50%;
        vertical-align: middle;
        margin-right: 0.4em;
        width: 35px;
        height: 35px;
        line-height: 35px;
        text-align: center;
        float: left;
    }

    .avatar-big {
        width: 70px;
        height: 70px;
        line-height: 70px;
        color: #222;
        position: fixed;
        z-index: 100;
    }

    table {
        width: 100% !important;
    }

    [data-tooltip]:before {
        text-align: left;
    }

    .head-date {
        font-size: 12px !important;
        font-weight: 700 !important;
        text-align: center !important;
    }

    .dt-column-order {

        display: none;
    }

    .dt-search {
        display: none;
    }

    .description {
        font-size: 12px;
        color: #777;
    }

    .head-title {
        font-size: 15px;
        font-weight: 600;

    }
</style>





<?php include_once 'content/puantaj-turleri-modal.php' ?>


<div class="container-xl mt-3">
    <form action="" method="post" id="puantajInfoForm">
        <div class="row g-2 align-items-center">

            <div class="col-md-3">
                <label for="projects" class="form-label">Proje:</label>
                <?php echo $projectHelper->getProjectSelect('projects', $project_id); ?>
            </div>
            <div class="col-md-3">
                <label for="projects" class="form-label">Ay:</label>
                <?php echo Date::getMonthsSelect('months', $month); ?>
            </div>
            <div class="col-md-3">
                <label for="projects" class="form-label">Yıl:</label>
                <?php echo Date::getYearsSelect('year', $year); ?>
            </div>
            <div class="col-md-3">
                <label for="projects" class="form-label">İşlem</label>
                <input type="radio" class="btn-check" name="btn-radio-toolbar" id="btn-radio-toolbar-1"
                    autocomplete="off">
                <label for="btn-radio-toolbar-1" data-tooltip="İndir" class="btn btn-icon" id="export_excel_puantaj">
                    <i class="ti ti-file-type-xls icon"></i>
                </label>

                <input type="radio" class="btn-check" name="btn-radio-toolbar" id="btn-radio-toolbar-1"
                    autocomplete="off">
                <label for="btn-radio-toolbar-1" data-tooltip="Yazdır" class="btn btn-icon">
                    <i class="ti ti-printer icon"></i>
                </label>

                <input type="radio" class="btn-check" name="btn-radio-toolbar" id="btn-radio-toolbar-1"
                    autocomplete="off">
                <label for="btn-radio-toolbar-1" data-tooltip="Personele Gönder" class="btn btn-icon">
                    <i class="ti ti-send icon"></i>
                </label>

                <?php if ($Auths->hasPermission('puantaj_data_entry')) { ?>
                    <button href="" type="button" class="btn btn-primary float-end" onclick="puantaj_olustur()">
                        <i class="ti ti-device-floppy icon me-2"></i> Kaydet
                    </button>
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
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-1" aria-expanded="false">
                                <h3 class="card-title">Puantaj +</h3>
                            </button>
                        </h2>
                        <div id="collapse-1" class="accordion-collapse collapse" data-bs-parent="#accordion-example">
                            <div class="accordion-body pt-0">
                                <strong>Tek tek seçim</strong> yapmak için ilgili alanlara tıklayınız! <br>
                                <strong>Çoklu seçim </strong> seçim yapmak için ilgili alanların üzerinde mouse basılı
                                şekilde tıklayınız!<br>
                                <strong>Seçim yaptıktan</strong> sonra puantaj türü listesinin açılmasını istiyorsanız
                                ctrl tuşu basılı şekilde seçim yapın

                            </div>
                        </div>
                    </div>

                    <div class="col-auto ms-auto">
                        <a href="#" class="btn" data-bs-toggle="modal" data-bs-target="#modal-default">
                            <i class="ti ti-plus icon me-2"></i> Puantaj Türleri
                        </a>

                    </div>
                </div>


                <div class="table-responsive">
                    <table id="puantajTable" class="table card-table text-nowrap datatable">
                        <thead>
                            <tr>
                                <th class="no-sort"></th>
                                <th></th>
                                <th style="display:none"></th>

                                <?php foreach ($dates as $date): ?>
                                    <?php
                                    $style = '';
                                    if (Date::isWeekend($date)) {
                                        $style = 'background-color:#99A98F;color:white';
                                    }
                                    echo ' <th class="gunadi" style="' . $style . '">' . Date::gunadi($date);
                                    '.</th>'
                                        ?>
                                <?php endforeach; ?>

                            </tr>
                            <tr>

                                <th class="ld">Adı Soyadı</th>
                                <th class="ld">Unvanı</th>
                                <th class="ld" style="display:none">Seç</th>
                                <?php
                                foreach ($dates as $date):
                                    $style = '';
                                    if (Date::isWeekend($date)) {
                                        $style = 'background-color:#99A98F;color:white';
                                    }
                                    echo '<th class="head-date" style="' . $style . '"><span>' . date('d', strtotime($date)) . '</span></th>';
                                    ?>

                                <?php endforeach; ?>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
                            foreach ($persons as $item):
                                $person = $personObj->find($item->id);
                                ?>
                                <tr>
                                    <td class="text-nowrap" style="min-width:12vw;" data-id="<?php echo $person->id ?>"><a
                                            class="btn-user-modal" type="button">
                                            <a href="index.php?p=persons/manage&id=<?php echo $person->id ?>"
                                                target="_blank"><?php echo $person->full_name ?></a></td>

                                    <td class="text-nowrap" style="min-width:10vw;">
                                        <?php echo $person->id ?>
                                    </td>


                                    <?php

                                    ?>

                                    <td class="text-nowrap" style="display:none">
                                        <input type="checkbox" name="checkbox_name" value="checkbox_value">
                                    </td>
                                    <?php
                                    foreach ($dates as $date):
                                        $jobStartDate = str_replace('-', '', Date::Ymd($person->job_start_date));
                                        $month_date = $gun = $date;

                                        ?>
                                        <?php

                                        if ($jobStartDate <= $month_date) {
                                            $puantaj_id = $puantajObj->getPuantajTuruId($person->id, $gun);


                                            if ($puantaj_id >= 0) {
                                                $puantaj_project = $puantajObj->getPuantajProjectId($person->id, $gun);
                                                $puantajHelper->puantajClass($puantaj_id, $project_id, $puantaj_project);
                                            } else {
                                                if (Date::isWeekend($date)) {
                                                    $puantajHelper->puantajClass(53);
                                                } else {
                                                    echo "<td class='gun noselect' data-project=''></td>";
                                                }
                                            }
                                        } else {
                                            echo "<td class='noselect text-center' style='background:#ddd'>---</td>";
                                        }
                                        ?>

                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>