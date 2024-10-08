<?php
define("ROOT", $_SERVER['DOCUMENT_ROOT']);
require_once ROOT . "/Model/Missions.php";
require_once ROOT . "/Model/MissionHeaders.php";
require_once ROOT . "/Model/User.php";
require_once ROOT . "/App/Helper/helper.php";

use App\Helper\Helper;

$users = new User();

$missionObj = new Missions();
$headerObj = new MissionHeaders();

$missionHeaders = $missionObj->getHeaderFromMissionsFirm($firm_id);

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

            .card {
                overflow: auto;
                white-space: wrap;
                border-radius: 6px;
                box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
                transition: all 0.3s;

            }

            .pointer {
                cursor: pointer;
            }
        </style>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">

                <div class="responsive d-flex" id="sortable">
                    <?php foreach ($missionHeaders as $item) { ?>
                        <?php
                        $mission_header_name = $headerObj->getMissionHeader($item->header_id)->header_name;

                        ?>

                        <div class="col-md-2 col-lg-2 me-3 header-item" id="item-<?php echo $item->header_id; ?>">
                            <div class="d-flex pointer">
                                <i class="ti ti-drag-drop icon me-1 "></i>
                                <h2 class="mb-3"> <?php echo $mission_header_name; ?></h2>
                            </div>
                            <?php
                            $missions = $missionObj->getMissionsByHeader($item->header_id);

                            ?>
                            <?php foreach ($missions as $mission) { ?>

                                <div class="mb-2 mission-items">
                                    <div class="row row-cards">
                                        <div class="col-12">
                                            <div class="card card-sm">
                                                <div class="card-body">
                                                   
                                                    <h3 class="card-title">
                                                        <label class="form-colorinput form-colorinput-light"
                                                        data-tooltip="Tamamlandı yap" 
                                                        >
                                                            <input name="color-rounded" type="radio" value="white"
                                                                class="form-colorinput-input" checked="">
                                                            <span class="form-colorinput-color bg-white rounded-circle"></span>
                                                        </label>
                                                   

                                                        <?php echo $mission->name; ?>
                                                    </h3>
                                                   
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