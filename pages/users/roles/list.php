<?php
require_once "Model/Roles.php";

$roleObj = new Roles();
$roles = $roleObj->getRolesByFirm($firm_id);


?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yetki Grupları</h3>
                    <div class="col-auto ms-auto">
                        <a href="#" class="btn btn-primary route-link" data-page="users/roles/manage">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>

                    </div>
                </div>

                <div class="table-responsive">
                    <table id="roleTable" class="table card-table table-responsive text-nowrap datatable">
                        <thead>
                            <tr>
                                <th style="width:7%">id</th>
                                <th>Pozisyon Adı</th>
                                <th>Açıklama</th>
                                <th>Durumu</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($roles as $role) :
                            ?>
                                <tr >
                                    <td><?php echo $role->id; ?></td>
                                    <td><?php echo $role->roleName; ?></td>
                                    <td><?php echo $role->roleDescription; ?></td>
                                    <td><?php echo $role->isActive; ?></td>

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item route-link" data-page="users/auths/auths&id=<?php echo $role->id ?>" href="#">
                                                    <i class="ti ti-lock icon me-3"></i> Yetkileri Düzenle
                                                </a>
                                                <a class="dropdown-item route-link" data-page="users/roles/manage&id=<?php echo $role->id ?>" href="#">
                                                    <i class="ti ti-edit icon me-3"></i> Güncelle
                                                </a>
                                                <a class="dropdown-item delete_role" href="#" data-id="<?php echo $role->id ?>">
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