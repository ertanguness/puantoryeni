<?php
require_once ROOT . "/Model/Missions.php";
require_once ROOT . "/Model/MissionHeaders.php";
require_once ROOT . "/Model/SettingsModel.php";
require_once ROOT . "/Model/UserModel.php";
require_once ROOT . "/App/Helper/helper.php";
require_once ROOT . "/App/Helper/users.php";

use App\Helper\Helper;

$userHelper = new UserHelper();


$userObj = new UserModel();
$settingsObj = new SettingsModel();

$missionObj = new Missions();
$headerObj = new MissionHeaders();

$is_done_visible = $settingsObj->getSettings("completed_tasks_visible")->set_value ?? 0;
$visible_button_text = $is_done_visible == 1 ? "Gizle" : "Göster";
$visible_button_icon = $is_done_visible == 1 ? "eye-off" : "eye";
$missionHeaders = $missionObj->getHeaderFromMissionsFirm($firm_id);



// Helper::dd($m_process);

if(!$Auths->Authorize("home_page_mission_view")) {
    Helper::authorizePage();
    return;
}

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
                        <a href="#" class="btn btn-primary" id="done-show">
                            <i class="ti ti-<?php echo $visible_button_icon?> icon me-1"></i>
                            <?php echo $visible_button_text; ?>
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

            .card-title {
                display: flex;
                align-items: center;
            }

            .form-colorinput {
                margin-right: 10px;
            }

            .avatar-xs {
                padding: 0 0.9rem;
            }

            .done {
                color: #28a745
            }

            .no-done {
                color: #EF5A6F
            }
        </style>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">

                <div class="responsive d-flex" id="sortable">
                    <?php foreach ($missionHeaders as $item) { ?>
                        <?php
                        $mission_header_name = $headerObj->getMissionHeader($item->header_id)->header_name;
                        //Eğer bu başlığın tamamlanmamış görevi yoksa başlığı ve is_done_visible = 1 ise gizle, değilse göster
                        if($is_done_visible == 0 && $missionObj->getUncompletedMissions($item->header_id)->count == 0) $display = "none";
                        else $display = "block";

                        if($missionObj->getUncompletedMissions($item->header_id)->count == 0) $color = "done";
                        else $color = "no-done";

                        ?>

                        <div class="col-md-2 col-lg-2 me-3 header-item" style="display:<?php echo $display?>" id="<?php echo $item->header_id; ?>">
                            <div class="d-flex pointer">
                                <i class="ti ti-drag-drop icon me-1 "></i>
                                <h3 class="mb-3"> <?php echo $mission_header_name; ?></h3>
                            </div>
                            <?php
                            $missions = $missionObj->getMissionsByHeader($item->header_id);

                            ?>
                            <?php foreach ($missions as $mission) {
                                $checked = $mission->status == 1 ? "checked" : "";
                                $color = $mission->status == 1 ? "done" : "no-done";
                                if($is_done_visible == 0 && $mission->status == 1) $display = "none";
                                else $display = "block";
                               
                                
                            ?>


                                <div class="mb-2 mission-items" style="display:<?php echo $display; ?>" id="<?php echo $mission->id; ?>">
                                    <div class="row row-cards">
                                        <div class="col-12">
                                            <div class="card card-sm">
                                                <div class="card-body">

                                                    <h3 class="card-title <?php echo $color ?>">
                                                        <label class="form-colorinput form-colorinput-light"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            data-bs-custom-class="custom-tooltip"
                                                            data-bs-title="Tamamlandı Yap">

                                                            <input name="color" type="checkbox" value="white"
                                                                data-mission-id="<?php echo $mission->id; ?>"
                                                                class="form-colorinput-input done-mission"
                                                                <?php echo $checked; ?>>
                                                            <span class="form-colorinput-color bg-white"></span>
                                                        </label>
                                                        <?php echo $mission->name ; ?>
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
                                                                    <?php
                                                                    $user_ids = $mission->user_ids;
                                                                    $user_ids = explode(",", $user_ids);
                                                                    $user_names = $userHelper->getUsersName($mission->user_ids);


                                                                    foreach ($user_ids as $user_id) {
                                                                        $user = $userObj->getUser($user_id);
                                                                    ?>
                                                                        <span class="avatar avatar-xs rounded"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            data-bs-custom-class="custom-tooltip"
                                                                            data-bs-title="<?php echo $user->full_name ?>">
                                                                            <?php echo Helper::getInitials($user->full_name ?? ''); ?>
                                                                        </span>


                                                                    <?php } ?>
                                                                </div>


                                                            </div>
                                                            <div class="col-auto text-secondary">
                                                                <button class="switch-icon switch-icon-scale"
                                                                    data-bs-toggle="switch-icon">
                                                                    <span class="switch-icon-a text-muted">
                                                                        <i class="ti ti-send icon"></i>
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
                    <?php
                    if(count($missionHeaders) == 0) { ?>
                       <div class="col">
                           <!-- static içindeki add-mission.svg'yi burada göster, Görev ekle yazısı ile beraber -->
                            <img src="./static/illustrations/to-do.avif" height="300" class="d-block mx-auto" alt="">
                            <h3 class="text-muted text-center">Firma için herhangi bir görev tanımlanmamış</h3>
                       </div>
                    <?php  }?>
                    
                </div>

            </div>
        </div>
    </div>
</div>
</div>