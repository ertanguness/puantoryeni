<?php
require_once "Model/TodoModel.php";
require_once "App/Helper/helper.php";
require_once "App/Helper/date.php";
use App\Helper\Date;

use App\Helper\Helper;
use App\Helper\Security;

$Todo = new Todo();
$todos = $Todo->all();

?>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yapılacaklar Listesi</h3>
                    <div class="col-auto ms-auto">
                        <a href="#" class="btn btn-primary route-link" data-page="todos/manage">
                            <i class="ti ti-plus icon me-2"></i> Yeni
                        </a>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table card-table text-nowrap datatable">
                        <thead>
                            <tr>
                                <th style="width:5%">Sıra</th>
                                <th>Adı</th>
                                <th>Türü</th>
                                <th>Açıklama</th>
                                <th>Eklenme Tarihi</th>
                                <th>İşlem</th>

                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($todos as $todo):
                                $id = Security::encrypt($todo->id);
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $todo->id ?></td>
                                    <td>
                                        <a class="route-link" data-page="todos/manage&id=<?php echo $id ?>" href="#">
                                            <?php echo $todo->title ?>
                                        </a>
                                    </td>
                                    <td><?php echo $todo->description ?></td>
                                    <td><?php echo $todo->status ?></td>
                                    <td><?php echo Date::dmY($todo->created_at) ?></td>

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top"
                                                data-bs-toggle="dropdown">İşlem</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item route-link"
                                                    data-page="todos/manage&id=<?php echo $id ?>" href="#">
                                                    <i class="ti ti-edit icon me-3"></i> Güncelle
                                                </a>
                                                <a class="dropdown-item delete-incexp" href="#" data-id="<?php echo $id ?>">
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