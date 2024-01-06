<?php

namespace Yeni\Controller;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \Yeni\Controller\AppController;
use Yeni\Library\Business\Purchasing;
use Yeni\Model\Table\SetProductTable;

class PurchasingController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->business_purchasing = new Purchasing();
    }

    public function index()
    {

    }

    public function importExcel()
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

                            $results[$key] = $this->business_purchasing->formatValue($key, $value, $getSheet);

                        }

                        if($this->business_purchasing->saveList($results))
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
        return $this->redirect('/yeni/purchasing/');
    }
}
