<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
// Create new PHPExcel object
$objPHPExcel = new Spreadsheet();
$objPHPExcel = IOFactory::load('uploads/template_excel/template_export_order.xlsx');
$objPHPExcel->getProperties()->setCreator("Admin");

//HEADER
$objPHPExcel->setActiveSheetIndex(0);
$i = 1;
$iq = 1;
if (!empty($results)) {
    foreach ($results as $key => $item) {
        $count_quoting = count($item->quoting);
        if($count_quoting <= 8) $count_quoting = 8;
        $merge = $i + $count_quoting;
        $objPHPExcel->getActiveSheet()->mergeCells("A$i:A$merge");

        $info = "Tên: " . $item->customer_name . "\r\n" .
                "ĐT: " . $item->customer_phone . "\r\n" .
                "ĐC: " . $item->customer_addr . "\r\n" .
                "Ghi chú: " . $item->note . "\r\n" .
                "Tiền ship: " .$item->shipping . "\r\n" .
                "Tiền thu khách: " . $item->total_order . "\r\n" .
                "Thực Nhận: " . $item->total_actual . "\r\n" .
                "Ngày: " . $item->order_date->toDateString();

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $info);

        //quoting
        foreach($item->quoting as $k => $quo)
        {
            $quo_name = "";
            if(!empty($quo->product))
            {
                $quo_name = $quo->product->name ;
            }else{
                $quo_name = $list_set_product[$quo->code] ;
            }
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $iq, $quo->code);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $iq, $quo_name);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $iq, $quo->quantity);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $iq, $quo->price);
            if ($k < count($item->quoting))
                $iq++;
        }

        $i = $i + $count_quoting + 2;

        $iq = $i;
    }
}

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
