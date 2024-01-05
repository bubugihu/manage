<?php

namespace Yeni\Controller;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \Yeni\Controller\AppController;
use Yeni\Library\Business\Report;

class ReportController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->business_report = new Report();
    }

    public function index()
    {
        $arr['key_search'] = $key_search = $_GET['key_search'] ?? "";
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)trim($_GET['page']) : 1;

        $list_orders = $this->business_report->getList($key_search, $page, false,1);
        $paginate = $this->Common->displayPaginationBelow(LIMIT, $page, $list_orders->count(), $arr);

        $this->set('total_orders',$list_orders->count());
        $this->set('list_orders',$list_orders->all()->toList());
        $this->set('paginate',$paginate);

        //chart
        $getMonthlyYear = $this->business_report->getMonthlyYear(2023);
        $labels = $this->business_report->matchingNumber();
        $this->set('labels',$labels);
        $this->set('getMonthlyYear',$getMonthlyYear);
    }

    public function importOrder()
    {
        if ($this->getRequest()->is('POST'))
        {
            $extensions = explode(".", $_FILES['file_import']['name']);
            $result = [];
            if ($extensions[1] == 'xlsx' || $extensions[1] == 'XLSX')
            {
                $file_name = $_FILES['file_import']['tmp_name'];
                $file = new Xlsx();
                $objPHPExcel = $file->load($file_name);
                // Customer sheet
                $getSheet = $objPHPExcel->getSheet(0);

                if (!empty($getSheet))
                {
                    $dataInput = $getSheet->toArray(null, true, true, true);
                    if (count($dataInput) > 0)
                    {
                        $results = [];

                        foreach ($dataInput as $key => $value)
                        {
                            if($key <= 1)
                            {
                                continue;
                            }

                            $results[$key] = $this->business_report->formatValueOrder($key, $value, $getSheet);

                        }

                        if($this->business_report->saveListOrder($results))
                        {
                            $this->Flash->success("Successfully.");
                        }else{
                            $this->Flash->error(__("Failed import"));
                        }
                    }
                    else
                    {
                        $this->Flash->error(__("Failed no data"));
                    }
                }
                else
                {
                    $this->Flash->error(__("Failed no sheet"));
                }
            }
            else
            {
                $this->Flash->error(__("Failed not xlsx"));
            }
        }
        return $this->redirect('/yeni/report/');
    }

    public function view($id)
    {

    }
}
