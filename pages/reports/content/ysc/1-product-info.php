<?php


require_once "App/Helper/report.php";
require_once "Model/ReportContent.php";

$products = new ReportContent();
$products = $products->getYscReportContent($id);



?>
<style>
    body {

        height: calc(270px) !important;

    }
</style>

<div class="table-responsive tableFixHead">

    <table id="yscTable" class="table ">
        <thead>
            <tr class="text-center">
                <th>İşlem</th>
                <th>Cihaz No </th>
                <th>Bulunduğu Bölge </th>
                <th>Cihaz Cinsi </th>
                <th colspan="2">Cihaz Kullanım Tarihleri </th>
                <th colspan="2">Kontrol Tarihleri </th>
                <th colspan="2">Kontrollerde Yapılan İşlemler</th>
                <th>Dış Muhafaza/Renk Kontrolü</th>
                <th>Çevre Kontrolü</th>
                <th>Pim Mühür Kontrolü</th>
                <th>Manometre Kontrolü</th>
                <th>Hortum/Nozül Kontrolü</th>
                <th>Talimat Kontrolü</th>
                <th>Ağırlık Kontrolü</th>
            </tr>
            <tr class="text-center">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Dolum Tarihi <?php echo $id; ?></th>
                <th>Son Kullanma Tarihi</th>
                <th>1.Kontrol Tarihi</th>
                <th>2.Kontrol Tarihi</th>
                <th>1.Kontrol</th>
                <th>2.Kontrol</th>
                <th colspan="8"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tabindex = 0;
            $productCount = count($products);
            if ($productCount == 0) {
                $products = [0];
            }

            foreach ($products as $product) {

            ?>
                <tr tabindex="<?php echo $tabindex; ?>" class="product-row">
                    <td style="display:none">
                        <input type="hidden" class="urun_id" value="<?php echo $product->id ?? 0; ?>" name="urun_id[]" id="urun_id">
                    </td>
                    <td class="pl-2">
                        <button type="button" data-id="<?php echo $product->id ?? 0; ?>" class="urun_sil btn"> <i class="ti ti-trash icon m-0"></i></button>
                    </td>
                    <td><input required type="text" class="form-control satir_no" id="cihaz_no" name="cihaz_no[]" value="<?php echo $product->cihaz_no ?? 1 ?>"></td>
                    <td>
                        <input type="text" class="form-control region" name="cihazbolge[]" value="<?php echo $product->bulundugu_bolge ?? '' ?>">
                    </td>
                    <td>
                        <input required type="text" class="form-control region" name="cinsi[]" value="<?php echo $product->cinsi ?? '' ?>">
                    </td>
                    <td data-tooltip="aa/yyyy veya aa-yyyy veya aa.yyyy şeklinde girebilirsiniz">
                        <input type="text" autocomplete="off" style="min-width:120px" class="form-control filling-date" placeholder="aa/yyyy" name="dolumtarihi[]" value="<?php echo $product->cihaz_dolum_tarihi ?? ''; ?>">
                    </td>
                    <td>
                        <input type="text" autocomplete="off" style="min-width:120px" class="form-control expiration-date " placeholder="aa/yyyy" name="sonkullanimtarihi[]" value="<?php echo $product->cihaz_sonkullanma_tarihi ?? ''; ?>">
                    </td>
                    <td>

                        <input type="text" autocomplete="off" class="form-control date-input" name="kontoltarihi1[]" value="<?php echo $product->kontrol_tarihi_1 ?? ''; ?>">
                    </td>
                    <td>
                        <input type="text" autocomplete="off" class="form-control date-input" name="kontoltarihi2[]" value="<?php echo $product->kontrol_tarihi_2 ?? ''; ?>">
                    </td>
                    <td style="min-width:170px">
                        <div class="input-group m-0 p-0">
                            <?php echo ReportHelper::islemSelect('islemkontroltarihi2[]', $product->islem_kontrol_tarihi_1 ?? ''); ?>
                        </div>

                    </td>
                    <td style="min-width:170px" class="d-flex">
                        <div class="input-group m-0 p-0">
                            <?php echo ReportHelper::islemSelect('islemkontroltarihi2[]', $product->islem_kontrol_tarihi_2 ?? ''); ?>
                        </div>

                    </td>

                    <td>
                        <?php echo ReportHelper::statusSelect('dismuhafaza[]', $product->dis_muhafaza ?? 1); ?>
                    </td>
                    <td>
                        <?php echo ReportHelper::statusSelect('cevrekontrolu[]', $product->cevre_kontrolu ?? 1); ?>
                    </td>
                    <td>
                        <?php echo ReportHelper::statusSelect('pimkontrolu[]', $product->pim_kontrolu ?? 1); ?>
                    </td>
                    <td>
                        <?php echo ReportHelper::statusSelect('manometrekontrolu[]', $product->manometre_kontrolu ?? 1); ?>
                    </td>
                    <td>
                        <?php echo ReportHelper::statusSelect('hortumkontrolu[]', $product->hortumkontrolu ?? 1); ?>
                    </td>

                    <td>
                        <?php echo ReportHelper::statusSelect('agirlikkontrolu[]', $product->talimat_kontrolu ?? 1); ?>
                    </td>
                    <td>
                        <?php echo ReportHelper::statusSelect('agirlikkontrolu[]', $product->agirlik_kontrolu ?? 1); ?>
                    </td>

                </tr>

            <?php

            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="16" class="pt-3 pb-3 pl-2">
                    <button type="button" class="btn btn-primary" id="add-product-row">Yeni Satır</button>
                    <a href="#" class="btn" data-bs-toggle="modal" data-bs-target="#modal-danger">
                        Çoklu Satır Ekle
                    </a>
                </td>
            </tr>
        </tfoot>
    </table>

</div>

<div class="modal modal-blur fade" id="modal-danger" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <i class="ti ti-alert-triangle icon"></i>
                <h3>Çoklu Satır Ekleme</h3>
                <div class="text-secondary">
                    Eklenecek satır sayısını aşağıdaki kutucuğa girin.
                    En fazla 100 satır ekleyebilirsinizi
                    Eklenecek sayı arttıkça programda yavaşlama olabilir
                </div>
                <input type="text" class="form-control mt-3" id="multi-row-count" placeholder="Satır sayısını girin">
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                Vazgeç
                            </a></div>
                        <div class="col"><a href="#" class="btn btn-danger w-100" id="add-product-multi-row" data-bs-dismiss="modal">
                                Ekle!
                            </a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>