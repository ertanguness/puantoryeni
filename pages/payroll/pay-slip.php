<?php

// Require composer autoload
ob_end_clean();
require_once ROOT . '/vendor/autoload.php';
// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => 10,
 
]);

$file = file_get_contents (__DIR__ . '/bordro.php');
//echo $file;
// Set a simple Footer including the page number
$mpdf->setFooter('{DATE j-m-Y}' . ' - ' . 'Sayfa {PAGENO}');
// Write some HTML code:
$mpdf->WriteHTML($file);

// Output a PDF file directly to the browser
$mpdf->Output();