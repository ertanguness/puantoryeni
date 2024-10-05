<?php
require_once "App/Helper/helper.php";
require_once "Model/Missions.php";

$missionObj = new Missions();

use App\Helper\Helper;

$missions = $missionObj->getMissionsFirm($firm_id);

?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Görevler Listesi</h3>
                    <div class="col-auto ms-auto">
                        <a href="#" class="btn btn-primary route-link" data-page="defines/service-head/manage">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table card-table text-nowrap datatable">
                        <thead>
                            <tr>
                                <th style="width:7%">ID</th>
                                <th>Görev Adı</th>
                                <th>Yetkili Kişi</th>
                                <th>Önem Derecesi</th>
                                <th>Başlama Tarihi</th>
                                <th>Bitiş Tarihi</th>
                                <th>Süreci</th>
                                <th>İşlem</th>

                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($missions as $item) :
                            ?>
                                <tr>
                                    <td>1</td>
                                    <td><?php echo $item->name; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item route-link" data-page="defines/incexp/manage&id=<?php echo $item->id ?>" href="#">
                                                    <i class="ti ti-edit icon me-3"></i> Güncelle
                                                </a>
                                                <a class="dropdown-item delete-incexp" href="#" data-id="<?php echo $item->id ?>">
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