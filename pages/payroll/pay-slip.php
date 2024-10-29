<?php
require_once ROOT . '/vendor/autoload.php';

ob_end_clean();
$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => 10,
 
]);


// Sayfa genişliğini al
$pageWidth = $mpdf->w;

$html = file_get_contents(ROOT . '/pages/payroll/bordro.php');

$mpdf->WriteHTML($html);

// Set a simple Footer including the page number
$mpdf->setFooter('{DATE j-m-Y H:i:s}' . ' - ' . 'Sayfa {PAGENO}');
$mpdf->Output();
?>