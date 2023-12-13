<?php
namespace App\Controller\Component;
use App\Library\Business\ExportExcel;
use Cake\Controller\Component;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CommonComponent extends Component
{
    function displayPaginationBelow($per_page, $page, $total = 0, $param = array())
    {
        $prr = '';
        if (isset($param) && count($param) > 0) {
            foreach ($param as $key => $value) {
                $prr .= '&' . $key . '=' . $value;
            }
        }

        $page_url = "?";
        /*$query = "SELECT COUNT(*) as totalCount FROM TABLE_NAME where status = 1";
        $rec = mysql_fetch_array(mysql_query($query));*/
        $total = $total;
        $adjacents = "2";

        $page = ($page == 0 ? 1 : $page);
        $start = ($page - 1) * $per_page;

        $prev = $page - 1;
        $next = $page + 1;
        $setLastpage = ceil($total / $per_page);
        $lpm1 = $setLastpage - 1;

        $setPaginate = "";
        if ($setLastpage > 1) {
            $setPaginate .= "<ul class='setPaginate'>";
            $setPaginate .= "<li class='setPage'>Page $page of $setLastpage</li>";
            if ($setLastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $setLastpage; $counter++) {
                    if ($counter == $page) {
                        $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                    } else {
                        $setPaginate .= "<li><a href='{$page_url}page=$counter{$prr}'>$counter</a></li>";
                    }
                }
            } elseif ($setLastpage > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page) {
                            $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                        } else {
                            $setPaginate .= "<li><a href='{$page_url}page=$counter{$prr}'>$counter</a></li>";
                        }
                    }
                    $setPaginate .= "<li class='dot'>...</li>";
                    $setPaginate .= "<li><a href='{$page_url}page=$lpm1{$prr}'>$lpm1</a></li>";
                    $setPaginate .= "<li><a href='{$page_url}page=$setLastpage{$prr}'>$setLastpage</a></li>";
                } elseif ($setLastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $setPaginate .= "<li><a href='{$page_url}page=1{$prr}'>1</a></li>";
                    $setPaginate .= "<li><a href='{$page_url}page=2{$prr}'>2</a></li>";
                    $setPaginate .= "<li class='dot'>...</li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page) {
                            $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                        } else {
                            $setPaginate .= "<li><a href='{$page_url}page=$counter{$prr}'>$counter</a></li>";
                        }
                    }
                    $setPaginate .= "<li class='dot'>..</li>";
                    $setPaginate .= "<li><a href='{$page_url}page=$lpm1{$prr}'>$lpm1</a></li>";
                    $setPaginate .= "<li><a href='{$page_url}page=$setLastpage{$prr}'>$setLastpage</a></li>";
                } else {
                    $setPaginate .= "<li><a href='{$page_url}page=1{$prr}'>1</a></li>";
                    $setPaginate .= "<li><a href='{$page_url}page=2{$prr}'>2</a></li>";
                    $setPaginate .= "<li class='dot'>..</li>";
                    for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter++) {
                        if ($counter == $page) {
                            $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                        } else {
                            $setPaginate .= "<li><a href='{$page_url}page=$counter{$prr}'>$counter</a></li>";
                        }
                    }
                }
            }

            if ($page < $counter - 1) {
                $setPaginate .= "<li><a href='{$page_url}page=$next{$prr}'>Next</a></li>";
                $setPaginate .= "<li><a href='{$page_url}page=$setLastpage{$prr}'>Last</a></li>";
            } else {
                $setPaginate .= "<li><a class='current_page' style='pointer-events: none; color: #000000; background-color: #ffffff'>Next</a></li>";
                $setPaginate .= "<li><a class='current_page'>Last</a></li>";
            }

            $setPaginate .= "</ul>\n";
        }


        return $setPaginate;
    }
    function exportExcel($type_template = null,$data = array(),$file = 'my_spreadsheet.xlsx',$output_type = 'D')
    {
        if(empty($data) || !is_array(TYPE_TEMPLATE[$type_template]))
        {
            return false;
        }

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        $randstring .= $characters[rand(10, strlen($characters) - 1)];

        $handle = new ExportExcel();
        switch (TYPE_TEMPLATE[$type_template]['template_name']) {
            case 'SALE_ORDER':
                $date = date('d M Y h:i A');
                $file = 'sale_order_'. $randstring.'_' . date('Y-m-d') . '.xlsx';
                $template = 'uploads/template_excel/sale_order.xlsx';
                $data_export = $handle->formatDataSaleOrder($data,TYPE_TEMPLATE[$type_template]['fields']);
                $data_note = $handle->formatDataSaleOrderFiledNotes($data);
                $i = 3;
                $y = 2;
                $k = 1;
                break;
            default:
                return false;
        }
        if(!$data_export)
            return false;
        $objPHPExcel = new Spreadsheet();
        $objPHPExcel = IOFactory::load($template);
        $objPHPExcel->getProperties()->setCreator("Admin");

        if (!empty($data_export))
        {
            foreach ($data_export as $item) {
                foreach ($item as $key => $value)
                {
                    /*
                     * $key : column , start = 1
                     * $i :row
                     */
                    $objPHPExcel->getSheet(0)->setCellValueByColumnAndRow($key, $i, $value);
                }
                if(!empty($data_note))
                {
                    $objPHPExcel->setActiveSheetIndex(1);
                    krsort($data_note);
                    foreach ($data_note as $key => $value)
                    {
                        if($key == 'count')
                        {
                            $objPHPExcel->getActiveSheet()->setCellValue('A' . 1, 'OD');
                            for ($x = 1; $x <= $value;$x++)
                            {
                                $note = 'Note '. $x;
                                $objPHPExcel->getSheet(1)->setCellValueByColumnAndRow($x + 1, 1, $note);
                            }
                            unset($data_note['count']);
                        }
                        if($key == $item[1])
                        {
                            foreach ($value as $key_item => $val_item)
                            {
                                $objPHPExcel->getSheet(1)->setCellValueByColumnAndRow($key_item, $y, $val_item);
                            }
                            $objPHPExcel->getSheet(1)->setCellValueByColumnAndRow(1, $y, $k++);
                            $y++;
                            unset($data_note[$key]);
                        }
                    }

                }
                $i++;
            }
        }
        $objPHPExcel->setActiveSheetIndex(0);
        if (isset($output_type) && $output_type == 'F') {
            $objWriter = new Xlsx($objPHPExcel);
            $objWriter->save($file);
        } else {
            // Redirect output to a client's web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $file . '"');
            header('Cache-Control: max-age=0');
            $objWriter = new Xlsx($objPHPExcel);
            $objWriter->save('php://output');
        }
        return true;
    }

    function displayPaginationLogBelow($per_page, $page, $total = 0, $param = array())
    {
        $prr = '';
        if (isset($param) && count($param) > 0) {
            foreach ($param as $key => $value) {
                $prr .= '&' . $key . '=' . $value;
            }
        }

        $page_url = "?";
        /*$query = "SELECT COUNT(*) as totalCount FROM TABLE_NAME where status = 1";
        $rec = mysql_fetch_array(mysql_query($query));*/
        $total = $total;
        $adjacents = "2";

        $page = ($page == 0 ? 1 : $page);
        $start = ($page - 1) * $per_page;

        $prev = $page - 1;
        $next = $page + 1;
        $setLastpage = ceil($total / $per_page);
        $lpm1 = $setLastpage - 1;

        $setPaginate = "";
        if ($setLastpage > 1) {
            $setPaginate .= "<ul class='setPaginate'>";
            $setPaginate .= "<li class='setPage'>Page $page of $setLastpage</li>";
            if ($setLastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $setLastpage; $counter++) {
                    if ($counter == $page) {
                        $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                    } else {
                        $setPaginate .= "<li><a href='{$page_url}page_log=$counter{$prr}'>$counter</a></li>";
                    }
                }
            } elseif ($setLastpage > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page) {
                            $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                        } else {
                            $setPaginate .= "<li><a href='{$page_url}page_log=$counter{$prr}'>$counter</a></li>";
                        }
                    }
                    $setPaginate .= "<li class='dot'>...</li>";
                    $setPaginate .= "<li><a href='{$page_url}page_log=$lpm1{$prr}'>$lpm1</a></li>";
                    $setPaginate .= "<li><a href='{$page_url}page_log=$setLastpage{$prr}'>$setLastpage</a></li>";
                } elseif ($setLastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $setPaginate .= "<li><a href='{$page_url}page_log=1{$prr}'>1</a></li>";
                    $setPaginate .= "<li><a href='{$page_url}page_log=2{$prr}'>2</a></li>";
                    $setPaginate .= "<li class='dot'>...</li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page) {
                            $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                        } else {
                            $setPaginate .= "<li><a href='{$page_url}page_log=$counter{$prr}'>$counter</a></li>";
                        }
                    }
                    $setPaginate .= "<li class='dot'>..</li>";
                    $setPaginate .= "<li><a href='{$page_url}page_log=$lpm1{$prr}'>$lpm1</a></li>";
                    $setPaginate .= "<li><a href='{$page_url}page_log=$setLastpage{$prr}'>$setLastpage</a></li>";
                } else {
                    $setPaginate .= "<li><a href='{$page_url}page_log=1{$prr}'>1</a></li>";
                    $setPaginate .= "<li><a href='{$page_url}page_log=2{$prr}'>2</a></li>";
                    $setPaginate .= "<li class='dot'>..</li>";
                    for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter++) {
                        if ($counter == $page) {
                            $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                        } else {
                            $setPaginate .= "<li><a href='{$page_url}page_log=$counter{$prr}'>$counter</a></li>";
                        }
                    }
                }
            }

            if ($page < $counter - 1) {
                $setPaginate .= "<li><a href='{$page_url}page_log=$next{$prr}'>Next</a></li>";
                $setPaginate .= "<li><a href='{$page_url}page_log=$setLastpage{$prr}'>Last</a></li>";
            } else {
                $setPaginate .= "<li><a class='current_page' style='pointer-events: none; color: #000000; background-color: #ffffff'>Next</a></li>";
                $setPaginate .= "<li><a class='current_page'>Last</a></li>";
            }

            $setPaginate .= "</ul>\n";
        }


        return $setPaginate;
    }
}
