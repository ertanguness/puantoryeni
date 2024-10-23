<?php
// require_once ROOT . "/Model/Persons.php";
// require_once ROOT . "/App/Helper/helper.php";
// require_once "App/Helper/security.php";
require ROOT . '/vendor/autoload.php';



use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$inputFileName = __DIR__ . '/xls/pay-slip.xlsx';

/** Load $inputFileName to a Spreadsheet object **/
$spreadsheet = IOFactory::load($inputFileName);
// Create new Spreadsheet object
// $spreadsheet = new Spreadsheet();

// Set document properties
// $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
//     ->setLastModifiedBy('Maarten Balliauw')
//     ->setTitle('PDF Test Document')
//     ->setSubject('PDF Test Document')
//     ->setDescription('Test document for PDF, generated using PHP classes.')
//     ->setKeywords('pdf php')
//     ->setCategory('Test result file');

// Add some data
// $spreadsheet->setActiveSheetIndex(0)
//     ->setCellValue('A1', 'Hello')
//     ->setCellValue('B2', 'world!')
//     ->setCellValue('C1', 'Hello')
//     ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A2', 'Miscellaneous glyphs')
    ->setCellValue('A3', 'éàèùâêîôûëïüÿäöüç');

// // Rename worksheet
// $spreadsheet->getActiveSheet()->setTitle('Simple');
// $spreadsheet->getActiveSheet()->setShowGridLines(false);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

IOFactory::registerWriter('Pdf', PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class);

ob_end_clean();
// Redirect output to a client’s web browser (PDF)
header('Content-Type: application/pdf');
header('Content-Disposition: attachment;filename="01simple.pdf"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Pdf');
$writer->save('php://output');
exit;