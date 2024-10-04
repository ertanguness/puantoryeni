<?php

// Output buffering başlatılır
ob_start();

require $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$inputFileName = $_SERVER["DOCUMENT_ROOT"] . '/files/example1.xlsx';

/** Load $inputFileName to a Spreadsheet object **/
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setCellValue('A2', 'Hello World !');

// Save the changes to the same file
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save($inputFileName);

// Clear output buffer to prevent any previous output
ob_clean();

// Set headers to force download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="example1.xlsx"');
header('Cache-Control: max-age=0');

// Read the file and output it to the browser
readfile($inputFileName);

// Output buffering sonlandırılır ve tampon boşaltılır
ob_end_flush();
exit;