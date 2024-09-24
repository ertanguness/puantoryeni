<?php
require_once "Model/Projects.php";
require_once "App/Helper/helper.php";
require_once "App/Helper/cities.php";

use App\Helper\Helper;

$projectObj = new Projects();
$cities = new Cities();
$projects = $projectObj->allWithFirm($firm_id);

?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Proje Listesi</h3>
                    <div class="col-auto ms-auto">
                        <div class="form-selectgroup">
                            
                            <label class="form-selectgroup-item">
                                <input type="radio" name="icons" value="user" class="form-selectgroup-input" checked>
                                <span class="form-selectgroup-label">
                                 <i class="ti ti-list-check icon me-2"></i>
                                    Tümü
                                </span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="icons" value="circle" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">
                                <i class="ti ti-download icon me-2"></i>
                                    Alınan Projeler
                                </span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="icons" value="square" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">
                                <i class="ti ti-upload icon me-2"></i>
                                    Verilen Projeler
                                </span>
                            </label>
                        </div>
                        <a href="#" class="btn btn-primary route-link" data-page="projects/manage">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table card-table text-nowrap datatable">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:10%">Id</th>
                                <th>Firma Adı</th>
                                <th>Proje Adı</th>
                                <th>Başlangıç Bütçesi</th>
                                <th>Şehir</th>
                                <th>İlçe</th>
                                <th style="width:10%">Başlama Tarihi</th>
                                <th style="width:10%">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($projects as $project) :
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $project->id ?></td>
                                <td><?php echo $project->firm_id ?></td>
                                <td><?php echo $project->project_name ?></td>
                                <td><?php echo Helper::formattedMoney($project->budget ?? 0) ?></td>
                                <td><?php echo $cities->getCityName($project->city) ?></td>
                                <td><?php echo $project->town ?></td>
                                <td><?php echo $project->start_date ?></td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle align-text-top"
                                            data-bs-toggle="dropdown">İşlem</button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item route-link"
                                                data-page="projects/add-person&id=<?php echo $project->id ?>" href="#">
                                                <i class="ti ti-users-plus icon me-3"></i> Projeye Personel Ekle
                                            </a>
                                            <a class="dropdown-item route-link"
                                                data-page="projects/manage&id=<?php echo $project->id ?>" href="#">
                                                <i class="ti ti-edit icon me-3"></i> Güncelle
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