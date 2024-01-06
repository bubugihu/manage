<?php

namespace Yeni\Controller;

use \Yeni\Controller\AppController;
use \Yeni\Model\Table\SetProductTable;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Yeni\Library\Business\Quoting;

class QuotingController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->business_quoting = new Quoting();
    }

    public function index()
    {
        $arr['key_search'] = $key_search = $_GET['key_search'] ?? "";
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)trim($_GET['page']) : 1;

        $list_quotings = $this->business_quoting->getList($key_search, $page, false, 0);
        $paginate = $this->Common->displayPaginationBelow(LIMIT, $page, $list_quotings->count(), $arr);

        $this->set('total_quotings',$list_quotings->count());
        $this->set('list_quotings',$list_quotings->all()->toList());
        $this->set('paginate',$paginate);
    }

    public function zalo()
    {
        $arr['key_search'] = $key_search = $_GET['key_search'] ?? "";
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)trim($_GET['page']) : 1;

        $list_quotings = $this->business_quoting->getList($key_search, $page, false,1);
        $paginate = $this->Common->displayPaginationBelow(LIMIT, $page, $list_quotings->count(), $arr);

        $this->set('total_quotings',$list_quotings->count());
        $this->set('list_quotings',$list_quotings->all()->toList());
        $this->set('paginate',$paginate);
    }

    public function total()
    {
        $arr['key_search'] = $key_search = $_GET['key_search'] ?? "";
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)trim($_GET['page']) : 1;

        $list_quotings = $this->business_quoting->getList($key_search, $page, false,null);
        $paginate = $this->Common->displayPaginationBelow(LIMIT, $page, $list_quotings->count(), $arr);

        $this->set('total_quotings',$list_quotings->count());
        $this->set('list_quotings',$list_quotings->all()->toList());
        $this->set('paginate',$paginate);
    }

    public function import()
    {
//        if ($this->getRequest()->is('POST'))
//        {
//            $extensions = explode(".", $_FILES['file_import']['name']);
//            $result = [];
//            if ($extensions[1] == 'xlsx' || $extensions[1] == 'XLSX')
//            {
//                $file_name = $_FILES['file_import']['tmp_name'];
//                $file = new Xlsx();
//                $objPHPExcel = $file->load($file_name);
//                // Customer sheet
//                $getSheet = $objPHPExcel->getSheet(0);
//                if (!empty($getSheet))
//                {
//                    $dataInput = $getSheet->toArray(null, true, true, true);
//                    if (count($dataInput) > 0)
//                    {
//                        $results = [];
//                        $set_product_model = new SetProductTable();
//                        $list_set_product = $set_product_model->find('list', [
//                            'fields' => ['id', 'code','del_flag'],
//                            'conditions' => ['SetProduct.del_flag' => UNDEL],
//                            'keyField' => 'code',
//                            'valueField' => function($value) {
//                                return $value;
//                            },
//                        ])->contain(['SetProductDetail'])->toArray();
//
//                        foreach ($dataInput as $key => $value)
//                        {
//                            if($key <= 1)
//                            {
//                                continue;
//                            }
//
//                            $results[$key] = $this->business_quoting->formatValue($key, $value, $getSheet);
//
//                        }
//
//                        if($this->business_quoting->saveList($results, $list_set_product))
//                        {
//                            $this->Flash->success("Successfully.");
//                        }else{
//                            $this->Flash->error(__("Failed import"));
//                        }
//                    }
//                    else
//                    {
//                        $this->Flash->error(__("Failed no data"));
//                    }
//                }
//                else
//                {
//                    $this->Flash->error(__("Failed no sheet"));
//                }
//            }
//            else
//            {
//                $this->Flash->error(__("Failed not xlsx"));
//            }
//        }
        return $this->redirect('/yeni/quoting/');
    }

    public function importZalo()
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
                        $set_product_model = new SetProductTable();
                        $list_set_product = $set_product_model->find('list', [
                            'fields' => ['id', 'code','del_flag'],
                            'conditions' => ['SetProduct.del_flag' => UNDEL],
                            'keyField' => 'code',
                            'valueField' => function($value) {
                                return $value;
                            },
                        ])->contain(['SetProductDetail'])->toArray();

                        $order_code = "";
                        foreach ($dataInput as $key => $value)
                        {
                            if($key <= 2)
                            {
                                continue;
                            }

                            if(!empty(trim($value['L'])))
                            {
                                $order_code = trim($value['L']);
                                $data = [
                                    'order_code'    =>  $order_code,
                                    'source'        => 1,
                                ];
                                $this->business_quoting->createNewOrder($data);
                            }

                            if(empty($value['G']))
                            {
                                if($value['H'] == "Ship")
                                {
                                    $fields = ['shipping' => trim($getSheet->getCell('J'.$key)->getValue())];
                                    $where = ['order_code' => $order_code];
                                    $this->business_quoting->updateByOrderCode($fields, $where);
                                }

                                if($value['H'] == "Tổng")
                                {
                                    $fields = ['total_order' => trim($getSheet->getCell('J'.$key)->getValue())];
                                    $where = ['order_code' => $order_code];
                                    $this->business_quoting->updateByOrderCode($fields, $where);
                                }

                                continue;
                            }

                            $results[$key] = $this->business_quoting->formatValueZalo($key, $value, $getSheet, $order_code);

                        }

                        if($this->business_quoting->saveList($results, $list_set_product))
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
        return $this->redirect('/yeni/quoting/zalo');
    }


}
