<?php

// Output buffering başlatılır
session_start();

define('ROOT', $_SERVER["DOCUMENT_ROOT"]);
$firm_id = $_SESSION['firm_id'];

require ROOT. '/vendor/autoload.php';
require_once ROOT . '/Model/Persons.php';

$personObj = new Persons();

$persons = $personObj->getPersonsByFirm($firm_id);


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$inputFileName = $_SERVER["DOCUMENT_ROOT"] . '/files/example1.xlsx';

/** Load $inputFileName to a Spreadsheet object **/
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

$activeWorksheet = $spreadsheet->getActiveSheet();

// Write Headers
$activeWorksheet->setCellValue('A2', 'Full Name');
$activeWorksheet->setCellValue('B2', 'Email');
$activeWorksheet->setCellValue('C2', 'Phone');
$activeWorksheet->setCellValue('D2', 'Job Start Date');
$activeWorksheet->setCellValue('E2', 'Daily Wages');
$activeWorksheet->setCellValue('F2', 'Wage Type');

// $cellRange = 'A3:Z33';
// $activeWorksheet->rangeToArray($cellRange, null, true, true, true);
// foreach ($activeWorksheet->getRowIterator(3, 33) as $row) {
//     foreach ($row->getCellIterator('A', 'Z') as $cell) {
//         $cell->setValue('');
//     }
// } 



foreach ($persons as $key => $person) {
    $activeWorksheet->setCellValue('A' . ($key+ 3 ), $person->full_name);
    $activeWorksheet->setCellValue('B' . ($key +3), $person->email);
    $activeWorksheet->setCellValue('C' . ($key +3), $person->phone);
    $activeWorksheet->setCellValue('D' . ($key +3), $person->job_start_date);
    $activeWorksheet->setCellValue('E' . ($key +3), $person->daily_wages);
    $activeWorksheet->setCellValue('F' . ($key +3), $person->wage_type);
}

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