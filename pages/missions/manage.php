<?php
require_once "Model/Missions.php";
require_once "App/Helper/users.php";
require_once "App/Helper/MissionsHelper.php";

$userHelper = new UserHelper();
$missionHelper = new MissionsHelper();
$missionObj = new Missions();
$id = $_GET['id'] ?? 0;
$mission = $missionObj->find($id);

$pageTitle = $id > 0 ? "Görev Güncelleme" : "Yeni Görev";
?>
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <?php echo $pageTitle; ?>
                    </h2>
                </div>

                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-outline-secondary route-link" data-page="missions/list">
                        <i class="ti ti-list icon me-2"></i>
                        Listeye Dön
                    </button>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-primary" id="saveMission">
                        <i class="ti ti-device-floppy icon me-2"></i>
                        Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- **************FORM**************** -->
                        <form action="" id="missionForm">
                            <!--********** HIDDEN ROW************** -->
                            <div class="row d-none">
                                <div class="col-md-4">
                                    <input type="text" name="id" class="form-control" value="<?php echo $id ?>">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="action" value="saveMission" class="form-control">
                                </div>
                            </div>
                            <!--********** HIDDEN ROW************** -->
                            <style>
                                .form-check {
                                    margin-right: 20px;
                                }

                                .row {
                                    margin-bottom: 10px;
                                }
                            </style>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="form-label">Görev Başlığı</label>
                                </div>
                                <div class="col-md-4">
                                    <?php
                                    $header_id = $mission->header_id ?? 0;
                                   echo $missionHelper->getMissionHeaderSelect("header_id", $header_id);
                                    
                                    ?>
                                </div>
                                <div class="col-md-2">
                                    <?php $priority = $mission->priority ?? 1; ?>

                                    <label class="form-label">Durum</label>
                                </div>

                                <div class="col-md-4 d-flex">
                                    <?php $status = $mission->status ?? 'active'; ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="priority" value="3" id="high"
                                            <?php echo $status == 'active' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="high">
                                            Yüksek
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="priority" value="2"
                                            id="middle" <?php echo $status == 'inactive' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="middle">
                                            Orta
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="priority" value="1" id="low"
                                            <?php echo $status == 'inactive' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="low">
                                            Düşük
                                        </label>
                                    </div>
                                </div>

                            </div> <!--row-->

                            <div class="row">
                                <div class="col-md-2">
                                    <label class="form-label">Görev Adı</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="name" class="form-control"
                                        value="<?php echo $mission->name ?? '' ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Görevi Atanacak Kişiler</label>

                                </div>
                                <div class="col-md-4">
                                    <?php
                                    // Veritabanından gelen user_ids değerini diziye dönüştür
                                    $user_ids = isset($mission->user_ids) ? explode(',', $mission->user_ids) : [];
                                    echo $userHelper->userSelectMultiple("user_ids[]", $user_ids);
                                    ?>

                                </div>
                            </div>


                            <!-- Başlama ve Bitiş Tarihleri -->
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="form-label">Başlama Tarihi</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" name="start_date" class="form-control flatpickr"
                                        value="<?php echo $mission->start_date ?? '' ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Bitiş Tarihi</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" name="end_date" class="form-control flatpickr"
                                        value="<?php echo $mission->end_date ?? '' ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label class="form-label">Göreve Eklenecek Not</label>
                                    <textarea class="form-control summernote" name="description"
                                        placeholder="Teklif altına bilgiliendirici not ekleyebilirsiniz">
                                        <?php echo $mission->description ?? ''; ?>
                                     </textarea>
                                </div>
                            </div>


                        </form>
                        <!-- **************FORM**************** -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>