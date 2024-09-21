<?php 
require_once "Model/Persons.php";
require_once "App/Helper/helper.php";

use App\Helper\Helper;

$id= isset($_GET['id']) ? $_GET['id'] : 0;

$person = new Persons();

$person = $person->find($id);


?>
<script>
    function print() {
           // payslip div'inin içeriğini al
           var divContents = document.getElementById("payslip").innerHTML;
        // mevcut sayfanın stilini al
        var styles = document.head.innerHTML;
        // yeni bir pencere aç
        var printWindow = window.open('', '', 'height=600,width=800');
        // yeni pencereye içeriği yaz
        printWindow.document.write('<html><head><title>Bordro</title>');
        printWindow.document.write(styles); // stil dosyalarını dahil et
        printWindow.document.write('</head><body>');
        printWindow.document.write(divContents);
        printWindow.document.write('</body></html>');
        // yazdırma işlemini başlat
        printWindow.document.close();
        printWindow.print();


    }
</script>
<div class="container-xl mt-3">
    <div id="payslip" class="col">
        <div id="title">Bordro</div>
        
        <div class="col-auto ms-auto">
            <button class="btn btn-primary" onclick="print()">Yazdır</button>
        </div>
        <div id="scope">
            <div class="scope-entry">
                <div class="title">TARİH</div>
                <div class="value">21.09.2024</div>
            </div>
            <div class="scope-entry">
                <div class="title">ÖDEME DÖNEMİ</div>
                <div class="value">EYLÜL - 2024</div>
            </div>
        </div>
        <div class="content">
            <div class="left-panel p-3">
                <div id="employee">
                    <div id="name">
                        PERSONEL BİLGİLERİ
                    </div>
                </div>
                <div class="details">
                    <div class="entry">
                        <div class="label">Tc Kimlik No</div>
                        <div class="value"><?php echo $person->id; ?></div>
                    </div>
                    <div class="entry">
                        <div class="label">Adı Soyadı</div>
                        <div class="value"><?php echo $person->full_name    ; ?></div>
                    </div>
                    <div class="entry">
                        <div class="label">Saatlik Ücret</div>
                        <div class="value"><?php echo Helper::formattedMoney($person->daily_wages / 8 ?? 0); ?></div>
                    </div>
                    <div class="entry">
                        <div class="label">Firma Adı</div>
                        <div class="value"><?php echo $person->firm_id; ?></div>
                    </div>
                    <div class="entry">
                        <div class="label">İşe Başlama Tarihi</div>
                        <div class="value"><?php echo $person->job_start_date; ?></div>
                    </div>
                    <div class="entry">
                        <div class="label">İşten Ayrılma Tarihi</div>
                        <div class="value"><?php echo $person->job_end_date; ?></div>
                    </div>
                    <div class="entry">
                        <div class="label">Görevi</div>
                        <div class="value"><?php echo $person->job; ?></div>
                    </div>
                    <div class="entry">
                        <div class="label">Bölümü</div>
                        <div class="value"><?php echo $person->job_group; ?></div>
                    </div>
                    <div class="entry">
                        <div class="label">Ödeme Türü</div>
                        <div class="value">Aylık</div>
                    </div>
                    
                </div>
                <div class="gross">
                    <div class="title">Brüt Gelir</div>
                    <div class="entry">
                        <div class="label"></div>
                        <div class="value">92,823.86</div>
                    </div>
                </div>
                <div class="contributions">
                    <div class="title">İşveren Katkısı</div>
                    <div class="entry">
                        <div class="label">SSS</div>
                        <div class="value">1,178.70</div>
                    </div>
                    <div class="entry">
                        <div class="label">SSS EC</div>
                        <div class="value">30.00</div>
                    </div>
                    <div class="entry">
                        <div class="label">HDMF</div>
                        <div class="value">100.00</div>
                    </div>
                    <div class="entry">
                        <div class="label">PhilHealth</div>
                        <div class="value">437.50</div>
                    </div>
                </div>
                <div class="ytd">
                    <div class="title">Yıl Bazında Toplamlar</div>
                    <div class="entry">
                        <div class="label">Toplam Gelir</div>
                        <div class="value">92,823.86</div>
                    </div>
                    <div class="entry">
                        <div class="label">Vergi Matrahı</div>
                        <div class="value">82,705.06</div>
                    </div>
                    <div class="entry">
                        <div class="label">Vergi Kesintisi</div>
                        <div class="value">21,548.85</div>
                    </div>
                    <div class="entry">
                        <div class="label">Kesintiler</div>
                        <div class="value">2,500.00</div>
                    </div>
                    <div class="entry">
                        <div class="label">Net Ödeme</div>
                        <div class="value">69,656.21</div>
                    </div>
                    
                </div>
            </div>

            <div class="center-panel p-3">
                <div id="employee">
                    <div id="name">
                        GELİR BİLGİLERİ
                    </div>
                </div>
                <div class="details">
                    <div class="entry">
                        <div class="label">Employee ID</div>
                        <div class="value">Reg-006</div>
                    </div>
                </div>
            </div>
            <div class="right-panel p-3">
                <div id="employee">
                    <div id="name">
                        KESİNTİ BİLGİLERİ
                    </div>
                </div>
                <div class="details">
                    <div class="entry">
                        <div class="label">Employee ID</div>
                        <div class="value">Reg-006</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>