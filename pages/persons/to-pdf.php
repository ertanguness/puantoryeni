<?php

session_start();
define('ROOT', $_SERVER["DOCUMENT_ROOT"]);
require ROOT. '/vendor/autoload.php';
require_once ROOT . '/Model/Persons.php';

$inputFileName = ROOT . '/files/example1.xlsx';

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$firm_id = $_SESSION['firm_id'];


$personObj = new Persons();
$persons = $personObj->getPersonsByFirm($firm_id);



/** Load $inputFileName to a Spreadsheet object **/
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

// Set document properties
$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
    ->setLastModifiedBy('Maarten Balliauw')
    ->setTitle('PDF Test Document')
    ->setSubject('PDF Test Document')
    ->setDescription('Test document for PDF, generated using PHP classes.')
    ->setKeywords('pdf php')
    ->setCategory('Test result file');

// Add some data





$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A2', 'Full Name')
    ->setCellValue('B2', 'Email')
    ->setCellValue('C2', 'Phone')
    ->setCellValue('D2', 'Job Start Date')
    ->setCellValue('E2', 'Daily Wages')
    ->setCellValue('F2', 'Wage Type');



foreach ($persons as $key => $person) {
    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A' . ($key + 3), $person->full_name)
        ->setCellValue('B' . ($key + 3), $person->email)
        ->setCellValue('C' . ($key + 3), $person->phone)
        ->setCellValue('D' . ($key + 3), $person->job_start_date)
        ->setCellValue('E' . ($key + 3), $person->daily_wages)
        ->setCellValue('F' . ($key + 3), $person->wage_type);
}


// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Simple');
$spreadsheet->getActiveSheet()->setShowGridLines(false);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

IOFactory::registerWriter('Pdf', PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class);
// IOFactory::registerWriter('Pdf', PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf::class);

// Redirect output to a client’s web browser (PDF)
header('Content-Type: application/pdf');
header('Content-Disposition: attachment;filename="01simple.pdf"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Pdf');
$writer->save('php://output');
exit;