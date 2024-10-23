<?php
require_once "App/Helper/helper.php";
require_once "App/Helper/date.php";
require_once "Model/Bordro.php";
require_once "Model/Puantaj.php";
require_once "Model/Projects.php";



use App\Helper\Helper;
use App\Helper\Date;

$bordro = new Bordro();
$puantajObj = new Puantaj();
$projects = new Projects();
$puantaj_info = $puantajObj->getPuantajInfoByPerson($id);


if(!$Auths->Authorize("person_page_puantaj_info")) {
    Helper::authorizePage();
    return;
}

?>
<style>
    table.datatable th,
    table.datatable td {
        text-align: left !important;
    }
</style>
<div class="container-xl mt-3">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Çalışma Bilgileri</h3>
                    <div class="col-auto ms-auto">
                        
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table card-table table-hover text-nowrap datatable">
                        <thead>
                            <tr>
                                <th style="width:7%">id</th>
                                <th >Proje</th>
                                <th >Puantaj Türü</th>
                                <th >Tarih</th>
                                <th >Saat</th>
                                <th class="text-start">Tutar</th>

                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($puantaj_info as $item):
                                ?>
                                <tr>
                                    <td><?php echo $item->id ?></td>
                                    <td><?php echo $projects->find($item->project_id )->project_name ?? '' ?></td>
                                    <td>
                                        <?php
                                        $puantaj_turu = $puantajObj->getPuantajTuruById($item->puantaj_id);
                                        echo $puantaj_turu->PuantajKod . " - " . $puantaj_turu->PuantajAdi;
                                        ?>
                                    </td>
                                    <td><?php echo Date::ymd($item->gun, "d.m.Y") ?></td>
                                    <td class="text-start"><?php echo $item->saat ?></td>
                                    <td class="text-start"><?php echo Helper::formattedMoney($item->tutar) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>