<?php
require_once "Model/Roles.php";

$roleObj = new Roles();
$id = $_GET["id"] ?? 0;
$roles = $roleObj->find($id);

$pageTitle = $id > 0 ? "Yetki Grubu Düzenle" : "Yeni Yetki Grubu";
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
                    <button type="button" class="btn btn-outline-secondary route-link" data-page="users/roles/list">
                        <i class="ti ti-list icon me-2"></i>
                        Listeye Dön
                    </button>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-primary" id="rol_kaydet">
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
                        <form action="" id="roleForm">
                            <div class="row mt-3">
                                <input type="hidden" class="form-control mb-3" id="role_id" value="<?php echo $id ?>">
                                <div class="col-md-2">
                                    <label class="form-label">Pozisyon Adı</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="role_name" value="<?php echo $roles->roleName ?? "" ?>">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-2">
                                    <label class="form-label">Pozisyon Açıklama</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="role_description" value="<?php echo $roles->roleDescription ?? "" ?>">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>