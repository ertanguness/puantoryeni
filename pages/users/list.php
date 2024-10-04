<?php
require_once "App/Helper/helper.php";
require_once "Model/User.php";

use App\Helper\Helper;

$userObj = new User();
$users = $userObj->getUsersByFirm($firm_id);

?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kullanıcı Listesi</h3>
                    <div class="col-auto ms-auto">
                        <a href="#" class="btn btn-primary route-link" data-page="users/manage">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>

                    </div>
                </div>

                <div class="table-responsive">
                    <table id="userTable" class="table card-table text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Pozisyon</th>
                                <th>Adı Soyadı</th>
                                <th>Email</th>
                                <th>Telefon</th>
                                <th>Durum</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($users as $user) :
                            ?>
                                <tr>
                                    <td><?php echo $user->id; ?></td>
                                    <td><?php echo $userObj->roleName($user->user_roles ?? ''); ?></td>
                                    <td><?php echo $user->full_name; ?></td>
                                    <td><?php echo $user->email; ?></td>
                                    <td><?php echo $user->phone; ?></td>
                                    <td><?php echo $user->status; ?></td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item route-link" data-page="users/manage&id=<?php echo $user->id ?>" href="#">
                                                    <i class="ti ti-edit icon me-3"></i> Güncelle
                                                </a>
                                                <a class="dropdown-item delete_user" data-id="<?php echo $user->id ?>" href="#">
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