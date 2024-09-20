<?php



require_once "App/Helper/customer.php";
require_once "App/Helper/users.php";
require_once "App/Helper/report.php";


$helper = new ReportHelper();
$customer = new customerHelper();
$users = new UserHelper();




?>


    <div class="alert alert-info" role="alert">
        Rapor bilgilerini doldurduktan sonra ürün bilgileri sayfasından tüp bilgilerini girebilirsiniz.
    </div>
    <div class="row mt-5">
        <div class="col-md-2 ">
            <label for="" class="align-middle">Rapor No</label>
        </div>
        <div class="col-md-4 mb-3">
            <input type="text" class="form-control" name="report_number" value="<?php echo $helper->getNumber('ysc'); ?>" id="report_number" required>
        </div>
        <div class="col-md-2">
            <label for="" class="align-middle">İş Emri No</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="job_order_no" id="job_order_no" value="<?php echo $report->isemrino ?? '';?>" required>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-2">
            <label for="" class="align-middle">Firma Adı</label>
        </div>
        <div class="col-md-4 mb-3">
            <?php echo $customer->customerSelect("customers", $report->customer_id ?? '')  ?>
        </div>
        <div class="col-md-2">
            <label for="" class="align-middle">Kontrol Eden Mühendis</label>
        </div>
        <div class="col-md-4">
            <?php echo $users->userSelectwithJob("controller_id", $report->controller_id ?? ''); ?>
        </div>

    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="" class="align-middle">Kontrol/Geçerlilik Tarihi</label>
        </div>
        <div class="col-md-2 mb-3">
            <input type="text" class="form-control flatpickr" name="control_date" id="control_date" value="<?php echo $report->control_date ?? '' ;?>" required>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control flatpickr" name="validity_date" id="validity_date" value="<?php echo $report->validity_date  ?? '';?>" required>
        </div>
        <div class="col-md-2">
            <label for="" class="align-middle">Onaylayan Yetkili</label>
        </div>
        <div class="col-md-4">
            <?php echo $users->userSelectwithJob("company_official", $report->company_official ?? ''); ?>
        </div>

    </div>
    <div class="row mt-3">
        <div class="col-md-2">
            <label for="" class="align-middle">Kontrol Periyodu</label>
        </div>
        <div class="col-md-4 mb-3">
            <input type="text" class="form-control" name="control_period" id="control_period" value="<?php echo $report->control_period ?? '' ;?>" required>
        </div>

    </div>


    <!-- İLGİLİ STANDARTLAR  -->
    <div class="form-group row">
        <label for="company" class="col-md-2">
            İlgili Standartlar
        </label>
        <div class="col-md-10">
            <div class="html-editor">
                <textarea style="height:100px; resize:vertical" name="standarts" id="standarts" class="summernote form-control" placeholder="İlgili Standartları yazınız">
                    <?php echo $report->standarts ?? ''; ?>
                </textarea><br>
            </div>
        </div>
    </div>
    <!-- İLGİLİ STANDARTLAR -->

    <!-- TEST SIRASINDA KULLANILAN EKİPMANLAR -->
    <div class="form-group row">
        <label for="company" class="col-md-2">
            Test Sırasında Kullanılan Ekipmanlar
        </label>
        <div class="col-md-10">

            <textarea style="height:100px; resize:vertical" name="equipment" class="form-control" placeholder="Ekipmanları yazınız"></textarea><br>

        </div>
    </div>
    <!--TEST SIRASINDA KULLANILAN EKİPMANLAR-->

    <!-- İKAZ VE UYARILAR -->
    <div class="form-group row">
        <label for="company" class="col-md-2">
            İkaz ve Uyarılar
        </label>
        <div class="col-md-10">
            <div class="html-editor">
                <textarea style="height:100px; resize:vertical" name="warnings" class="summernote form-control" placeholder="İkaz ve uyarıları yazınız"></textarea><br>
            </div>
        </div>
    </div>
    <!-- İKAZ VE UYARILAR -->

    <!-- SONUÇ VE KANAAT-->
    <div class="form-group row">
        <label for="company" class="col-md-2">
            Sonuç ve Kanaat
        </label>
        <div class="col-md-10">
            <textarea style="height:100px; resize:vertical" name="notes" class="form-control" placeholder="Rapor hakkında not yazınız"></textarea>

        </div>
    </div>
    <!-- SONUÇ VE KANAAT-->

    <!-- SONUÇ VE KANAAT-->
    <div class="form-group row mt-3">
        <label for="company" class="col-md-2">
            Alt Bilgi
        </label>
        <div class="col-md-10">
            <textarea style="height:100px; resize:vertical" name="subNotes" class="summernote form-control" placeholder="Alt Bilgi">Yukarıda kontrol tarihinde teknik özellikleri belirtilen Yangın Söndürme Sistemi ; mevcut şartlar altında 25 Nisan 2013 tarih ve 28628 sayılı resmi gazetede yayınlanan "İş Ekipmanlarının Kullanımında Sağlık Ve Güvenlik Şartları Yönetmeliği" nde belirtilen kriterlere uygun olarak TS ISO 11602-2 standartı 4.2 maddesine göre Periyodik Kontrol'ü yapılmıştır. Bu kontrol raporu muayene tarihindeki durumunu yansıtır, uygunluğun devamlılığından işveren sorumludur.Yangın söndürme cihazlarının kullanılmasında teknik olarak sakınca yoktur
                </textarea><br>

        </div>
    </div>
    <!-- SONUÇ VE KANAAT-->
