<?php
$user_id = $_SESSION['user']->id;
require_once "Model/Company.php";

$companyObj = new Company();
$myfirms = $companyObj->getMyCompanies($user_id);

?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Firmalarım Listesi</h3>
                    <div class="col-auto ms-auto">
                        <a href="#" class="btn btn-icon me-2" data-tooltip="Excele Aktar">
                            <i class="ti ti-file-excel icon"></i>
                        </a>
                        <a href="#" class="btn btn-primary route-link" data-page="mycompany/manage">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table card-table text-nowrap datatable">
                        <thead>
                            <tr>
                                <th style="width:1%">id</th>
                                <th>Firma Adı</th>
                                <th>telefon</th>
                                <th>Mail Adresi</th>
                                <th>Açıklama</th>
                                <th>Oluşturulma Tarihi</th>
                                <th style="width:1%">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($myfirms as $myfirm) :
                            ?>
                                <tr>
                                    <td><?php echo $myfirm->id; ?></td>
                                    <td><a class="btn route-link" data-page="mycompany/manage&id=<?php echo $myfirm->id ?>" href="#">
                                            <?php echo $myfirm->firm_name; ?>
                                        </a></td>
                                    <td><?php echo $myfirm->phone; ?></td>
                                    <td><?php echo $myfirm->email; ?></td>
                                    <td><?php echo $myfirm->description; ?></td>
                                    <td><?php echo $myfirm->created_at; ?></td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item route-link" data-page="mycompany/manage&id=<?php echo $myfirm->id ?>" href="#">
                                                    <i class="ti ti-edit icon me-3"></i> Güncelle
                                                </a>
                                                <a class="dropdown-item delete-mycompany" data-id="<?php echo $myfirm->id ?>" href="#">
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