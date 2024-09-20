<?php

use App\Helper\Helper;

require_once "App/Helper/customer.php";
require_once "App/Helper/products.php";
require_once "App/Helper/helper.php";
require_once "Model/Offer.php";



$customer = new customerHelper();
$product = new Products();
$offer = new Offer();
?>

<div class="modal modal-blur fade" id="modal-success" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header">
                <div class="modal-title">Select Product</div>
            </div>
            <div class="modal-status bg-success"></div>
            <div class="modal-body text-center py-4">
                <div class="form-floating">
                    <?php echo $product->productSelect("products") ?>
                    <label for="products">Ürün Adı</label>
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                Vazgeç
                            </a></div>
                        <div class="col"><a href="#" id="urun_sec" class="btn btn-success w-100" data-bs-dismiss="modal">
                                Ürünü Seç
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Invoice
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-primary" id="teklif_kaydet">

                        Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <form action="" id="offerForm">
                <input type="hidden" id="offer_id" value="0">

                <div class="card card-lg">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">

                                <input type="text" class="form-control text-center font-bold" value="FİYAT TEKLİF FORMU">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6">
                                <p class="h3"> <?php echo $customer->customerSelect(); ?></p>

                                <address>
                                    <span><i class="ti ti-phone icon me-2"></i>Phone</span><br>
                                    <span><i class="ti ti-building icon me-2"></i>State, City</span><br>
                                    <span><i class="ti ti-mail icon me-2"></i> info@gmail.com</span><br>
                                    <span><i class="ti ti-user icon me-2"></i>İlknur Hanım</span><br>

                                </address>
                            </div>

                            <div class="col-6">



                                <div class="float-end">
                                    <input type="text" name="offerNumber" placeholder="Teklif numarası" class="form-control invoice-input mb-2">


                                    <input type="text" name="offerDate" placeholder="Tarih seçiniz" class="form-control invoice-input mb-2 flatpickr">

                                    <input type="text" name="offerReference" placeholder="Teklif Referansı" class="form-control invoice-input mb-2">

                                </div>

                            </div>
                            <div class="col-12">
                                <textarea class="form-control summernote">Sayın {Firma_Yetkilisi} talep etmiş olduğunuz ürün/hizmetlere ilişkin fiyat teklifimiz aşağıda bilgilerinize sunulmuştur. Fiyatlarımızın makul gelmesini umut eder, iyi çalışmalar dileriz.</textarea>
                            </div>
                            <table class="table table-transparent table-responsive">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 1%"></th>
                                        <th>Product</th>
                                        <th class="text-center" style="width:21%">Qnt</th>
                                        <th class="text-end" style="width: 2%">Unit</th>
                                        <th class="text-end" style="width: 15%">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <div class="row" id="invoice-item-list">
                                            <div class="row invoice-row mt-3 ">

                                                <div class="col-1 flex-column link-secondary justify-content-between nav-link">
                                                    <i class="ti ti-x remove-item icon cursor-pointer "></i>
                                                    <label for="" class="order-number">1</label>
                                                    <i class="ti ti-arrows-move cursor-pointer move-item icon"></i>
                                                </div>

                                                <div class="col-3 flex-column">

                                                    <div class="form-floating d-flex mb-2">
                                                        <input type="text" class="form-control urun_adi" name="urun_adi[]" id="urun_adi" autocomplete="off">
                                                        <label for="urun_adi">Ürün Adı</label>

                                                        <span class="nav-link urun_sec_icon" data-bs-toggle="modal" data-bs-target="#modal-success">
                                                            <i class="ti ti-barcode icon fs-24 link-secondary ms-2" data-bs-toggle="tooltip" title="Listeden Ürün Seçiniz"></i>
                                                        </span>
                                                    </div>

                                                    <div class="form-floating flex-row">
                                                        <input type="text" class="form-control" name="stok_kodu[]" id="floating-input" value="" autocomplete="off">
                                                        <label for="floating-input">Stok Kodu</label>
                                                    </div>


                                                </div>
                                                <div class="col-2 flex-column ">
                                                    <div class="form-floating flex-row">
                                                        <input type="text" class="form-control mb-2 quantity" name="urun_miktari[]" id="urun_miktari" value="" autocomplete="off">
                                                        <label for="urun_miktari">Miktar</label>
                                                    </div>
                                                    <div class="form-floating flex-row">
                                                        <?php echo Helper::unitSelect("urun_birim[]"); ?>
                                                        <label for="units">Birimi</label>
                                                    </div>
                                                </div>
                                                <div class="col-2 flex-column ">
                                                    <div class="form-floating flex-row">
                                                        <input type="text" class="form-control mb-2 satis_fiyati" name="satis_fiyati[]" id="floating-input" value="" autocomplete="off">
                                                        <label for="floating-input">Satış Fiyatı</label>
                                                    </div>
                                                    <div class="form-floating flex-row">
                                                        <?php echo Helper::moneySelect("satis_para_birimi[]"); ?>
                                                        <label for="floating-input">Satış Para Birimi</label>
                                                    </div>
                                                </div>
                                                <div class="col-2 flex-column ">
                                                    <div class="form-floating flex-row">
                                                        <input type="text" class="form-control mb-2 alis_fiyati" name="alis_fiyati[]" id="alis_fiyati" value="" autocomplete="off">
                                                        <label for="alis_fiyati">Alış Fiyatı</label>
                                                    </div>
                                                    <div class="form-floating flex-row">
                                                        <?php echo Helper::moneySelect("alis_para_birimi[]"); ?>
                                                        <label for="moneys">Alış Para Birimi</label>
                                                    </div>
                                                </div>

                                                <div class="col-2 ">
                                                    <div class="form-floating flex-row">

                                                        <input type="text" class="form-control total-row text-center" style="height:124px;font-size:20px" id="floating_toplam" value="" autocomplete="off">
                                                        <label for="floating_toplam ms-1">Toplam Fiyatı</label>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-2 d-block">
                                                <input type="text" class="form-control" style="height:124px;font-size:20px">
                                            </div> -->
                                            </div>

                                        </div>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="strong text-end">

                                            <button type="button" class="btn btn-primary" id="add-invoice-item">Add Item</button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="4" class="strong text-end">Ara Toplam</td>
                                        <td class="text-end" id="alt_toplam">0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="strong text-end">
                                            <div class="col-2 float-end">
                                                <div class="form-floating flex-row">
                                                    <?php echo Helper::kdvSelect('kdv_orani'); ?>
                                                    <label for="kdv_orani ms-1">Kdv Oranı</label>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="font-weight-bold text-end" id="kdv_tutari">0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="font-weight-bold text-uppercase text-end">Genel Toplam</td>
                                        <td class="font-weight-bold text-end">
                                            <label for="" id="genel_toplam"></label>
                                            <input type="hidden" name="genel_toplam_input" id="genel_toplam_input" readonly>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mb-3">
                                <label class="form-label">Teklif altı not:</label>
                                <textarea class="form-control summernote" placeholder="Teklif altına bilgiliendirici not ekleyebilirsiniz">

                            </textarea>
                            </div>

                            <p class="text-secondary text-center mt-5">Thank you very much for doing business with us. We look forward to working with
                                you again!</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>


</script>