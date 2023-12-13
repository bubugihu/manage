<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
// Create new PHPExcel object
$objPHPExcel = new Spreadsheet();
$objPHPExcel = IOFactory::load('uploads/template_excel/template_export_product.xlsx');
$objPHPExcel = IOFactory::load('uploads/template_excel/inventory_demo.xlsx');
$objPHPExcel->getProperties()->setCreator("Admin");

$defaut_value = $config[1]->value;
//HEADER
$objPHPExcel->setActiveSheetIndex(0);
$i = 6;
if (!empty($results)) {
    foreach ($results as $key => $item) {
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $key + 1);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, (!empty($item->category) ? $item->category : ''));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, (!empty($item->code) ? $item->code : ''));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, (!empty($item->name) ? $item->name : ''));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $item->import_price_display);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $item->export_price_display);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $item->p_qty);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $item->export_price_display);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $item->q_qty);
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $item->total_display);
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $item->t_qty);
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $item->updated_on->toDateTimeString());

        if ($item->total_display <= $defaut_value)
        {
            $cellCoordinate = 'I' . $i;
            $objPHPExcel->getActiveSheet()->getStyle($cellCoordinate)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
        }
        if ($key < count($results))
            $i++;
    }
}

$objPHPExcel->getActiveSheet()->getStyle("A6:K" . ($i - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
//call the function in the controller with $output_type = F and $file with complete path to the file, to generate the file in the server for example attach to email
if (isset($output_type) && $output_type == 'F') {
    $objWriter = new Xlsx($objPHPExcel);
    $objWriter->save($file_name);
} else {
    // Redirect output to a client's web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $file_name . '"');
    header('Cache-Control: max-age=0');
    $objWriter = new Xlsx($objPHPExcel);
    $objWriter->save('php://output');
}
