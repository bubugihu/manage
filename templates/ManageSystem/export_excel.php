<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
// Create new PHPExcel object
$objPHPExcel = new Spreadsheet();
$objPHPExcel = IOFactory::load('uploads/template_excel/customers.xlsx');
$objPHPExcel->getProperties()->setCreator("Admin");

//HEADER
$objPHPExcel->setActiveSheetIndex(0);
$i = 7;
if (!empty($results)) {
    foreach ($results as $key => $item) {
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $key + 1);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, (!empty($item->code) ? $item->code : ''));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, (!empty($item->last_name) ? strtoupper($item->last_name) : ''));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, (!empty($item->first_name) ? strtoupper($item->first_name) : ''));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, (!empty($item->year) ? $item->year : ''));
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, (!empty($item->month) ? $item->month : ''));
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, (!empty($item->day) ? $item->day : ''));
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, (!empty($item->gender_display) ? $item->gender_display : ''));
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, (!empty($item->cccd) ? $item->cccd : ''));
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, "0934093001");
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, "bubugihu@gmail.com");
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, (!empty($item->level) ? $item->level : ''));
        if ($key < count($results))
            $i++;
    }
}
//$i++;

//vẽ đường viền
//$objPHPExcel->getActiveSheet()->getStyle("A2:Q" . ($i - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);


//$i++;
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
