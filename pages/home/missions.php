<?php
define("ROOT", $_SERVER['DOCUMENT_ROOT']);
require_once ROOT . "/Model/Missions.php";
require_once ROOT . "/Model/MissionProcess.php";
require_once ROOT . "/Model/MissionProcessMapping.php";
require_once ROOT . "/Model/User.php";
require_once ROOT . "/App/Helper/helper.php";

use App\Helper\Helper;

$users = new User();

$missionObj = new Missions();
$process = new MissionProcess();
$mapping = new MissionProcessMapping();

$missions = $missionObj->getMissionsFirm($firm_id);
$m_process = $process->getMissionProcessFirm($firm_id);

// Helper::dd($m_process);
?>


<div class="table-responsive">

    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Görevler
                        </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <a href="#" class="btn btn-primary">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            Add board
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .responsive {
                overflow: auto;
                white-space: nowrap;
            }

            .card-container {
                display: inline-block;
                vertical-align: top;
                margin-right: 10px;
            }
        </style>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">

                <div class="responsive d-flex">
                    <?php foreach ($m_process as $item) { ?>
                        <?php $isProcessFromMapping = $process->getMissionProcessFromMapping($item->id) ?>
                        <?php if (empty($isProcessFromMapping)) {
                            continue;
                        } ?>

                        <div class="col-md-3 col-lg-3 me-3">
                            <h2 class="mb-3"><?php echo $item->process_order . "-" .  $item->process_name; ?></h2>
                            <!-- Görevler listeliyoruz, süreç tablosundaki son süreç id'sine göre görevleri listeliyoruz. -->
                            <?php foreach ($missions as $mission) { ?>

                                <?php $mission_process = $mapping->getMissionProcessMapByLastProcessId($mission->id);
                                // Eklenen görev süreç tablosundaki son süreç id'sine göre getirilir yoksa gösterilmez. 
                                $process_id = $mission_process[0]->process_id ?? 0;
                                ?>
                                <?php if ($process_id != $item->id) {
                                    continue;
                                } ?>



                                <div class="mb-4">
                                    <div class="row row-cards">
                                        <div class="col-12">
                                            <div class="card card-sm">
                                                <div class="card-body">
                                                    <h3 class="card-title"><?php echo $mission->name; ?></h3>
                                                    <div class="card-subtitle text-muted">
                                                        <span><?php echo $mission->start_date . "-" . $mission->end_date; ?></span>
                                                    </div>
                                                    <div class="card-subtitle text-muted">
                                                        <span><?php echo $mission->description; ?></span>
                                                    </div>
                                                    <div class="mt-4">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="avatar-list avatar-list-stacked">
                                                                    <span class="avatar avatar-xs rounded">EP</span>
                                                                    <span class="avatar avatar-xs rounded"
                                                                        style="background-image: url(./static/avatars/002f.jpg)"></span>
                                                                    <span class="avatar avatar-xs rounded"
                                                                        style="background-image: url(./static/avatars/003f.jpg)"></span>
                                                                    <span class="avatar avatar-xs rounded">HS</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto text-secondary">
                                                                <button class="switch-icon switch-icon-scale"
                                                                    data-bs-toggle="switch-icon">
                                                                    <span class="switch-icon-a text-muted">
                                                                        <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon">
                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                            </path>
                                                                            <path
                                                                                d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                    <span class="switch-icon-b text-red">
                                                                        <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon icon-filled">
                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                            </path>
                                                                            <path
                                                                                d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </button>
                                                                7
                                                            </div>
                                                            <div class="col-auto">
                                                                <a href="#"
                                                                    class="link-muted"><!-- Download SVG icon from http://tabler-icons.io/i/message -->
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                        </path>
                                                                        <path d="M8 9h8"></path>
                                                                        <path d="M8 13h6"></path>
                                                                        <path
                                                                            d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z">
                                                                        </path>
                                                                    </svg>
                                                                    2</a>
                                                            </div>
                                                            <div class="col-auto">
                                                                <a href="#"
                                                                    class="link-muted"><!-- Download SVG icon from http://tabler-icons.io/i/share -->
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                        </path>
                                                                        <path d="M6 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0">
                                                                        </path>
                                                                        <path d="M18 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0">
                                                                        </path>
                                                                        <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0">
                                                                        </path>
                                                                        <path d="M8.7 10.7l6.6 -3.4"></path>
                                                                        <path d="M8.7 13.3l6.6 3.4"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                            <?php } ?> <!-- end of foreach missions -->
                        </div>
                    <?php } ?> <!-- end of foreach m_process -->
                </div>

            </div>
        </div>
    </div>
</div>
</div>