<?php

namespace Yeni\Controller;

use \Yeni\Controller\AppController;
use Yeni\Library\Business\Orders;
use Yeni\Model\Table\ProductTable;
use \Yeni\Model\Table\SetProductTable;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Yeni\Library\Business\Quoting;

class QuotingController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->business_quoting = new Quoting();
        $this->model_product = new ProductTable();
        $this->business_order = new Orders();
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

    public function importShopee()
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
                        // list product
                        $list_product = $this->model_product->find('list', [
                            'fields' => ['id', 'code','del_flag','p_price'],
                            'conditions' => ['Product.del_flag' => UNDEL],
                            'keyField' => 'code',
                            'valueField' => function($value) {
                                return $value['p_price'];
                            },
                        ])->toArray();

                        //set product
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
//                            if($key <= 1 || empty($value['A']) || $value['D'] != "Hoàn thành")
//                            if($key <= 1 || empty($value['A']) || $value['D'] != "Đã hủy")
                            if($key <= 1 || empty($value['A']))
                            {
                                continue;
                            }

                            $results['quoting'][$key] = $this->business_quoting->formatValueQuotingShopee($key, $value, $getSheet, $order_code, $list_product);
                            $result_order = $this->business_quoting->formatValueOrderShopee($key, $value, $getSheet, $order_code);
                            if(!empty($result_order))
                            {
                                $results['order'][$key] = $result_order;
                            }

                        }

                        if($this->business_quoting->saveListImportShopee($results, $list_set_product))
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
        return $this->redirect('/yeni/quoting/');
    }

    public function importZalo()
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
//
//                if (!empty($getSheet))
//                {
//                    $dataInput = $getSheet->toArray(null, true, true, true);
//
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
//                        $order_code = "";
//                        foreach ($dataInput as $key => $value)
//                        {
//                            if($key <= 2)
//                            {
//                                continue;
//                            }
//
//                            if(!empty(trim($value['L'])))
//                            {
//                                $order_code = trim($value['L']);
//                                $data = [
//                                    'order_code'    =>  $order_code,
//                                    'source'        => 1,
//                                ];
//                                $this->business_quoting->createNewOrder($data);
//                            }
//
//                            if(empty($value['G']))
//                            {
//                                if($value['H'] == "Ship")
//                                {
//                                    $fields = ['shipping' => trim($getSheet->getCell('J'.$key)->getValue())];
//                                    $where = ['order_code' => $order_code];
//                                    $this->business_quoting->updateByOrderCode($fields, $where);
//                                }
//
//                                if($value['H'] == "Tổng")
//                                {
//                                    $fields = ['total_order' => trim($getSheet->getCell('J'.$key)->getValue())];
//                                    $where = ['order_code' => $order_code];
//                                    $this->business_quoting->updateByOrderCode($fields, $where);
//                                }
//
//                                continue;
//                            }
//
//                            $results[$key] = $this->business_quoting->formatValueZalo($key, $value, $getSheet, $order_code);
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
        return $this->redirect('/yeni/quoting/zalo');
    }


    public function updateShopee()
    {
        if ($this->getRequest()->is('POST'))
        {
            //get order stt 1 pending
            $condition = [
                'status'    =>  STATUS_QUOTING_PROCESS,
                'del_flag'    =>  UNDEL,
            ];
            $list_order_pending = $this->business_order->model_order->find()->where($condition)->all()->toList();

            foreach($list_order_pending as $key => $value)
            {
                $list_order_pending[substr($value->order_code, 7)] = $value->order_code;
                unset($list_order_pending[$key]);
            }

            $list_cancel = [];
            $list_done = [];
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
                        foreach ($dataInput as $key => $value)
                        {
                            if(!empty($list_order_pending[$value['A']]))
                            {
                                if($value['D'] == "Hoàn thành")
                                {
                                    if(!in_array($list_order_pending[$value['A']], $list_done))
                                        $list_done[] = $list_order_pending[$value['A']];
                                }
                                else
                                {
                                    if(!in_array($list_order_pending[$value['A']], $list_cancel))
                                        $list_cancel[] = $list_order_pending[$value['A']];
                                }
                            }
                        }

                        if(!empty($list_done))
                        {
                            $condition_done = [
                                'order_code IN' =>  $list_done
                            ];
                            $set = [
                                'status'    =>  STATUS_DONE
                            ];
                            $this->business_quoting->model_quoting->updateAll($set,$condition_done);
                            $this->business_quoting->model_order->updateAll($set,$condition_done);
                        }

                        if(!empty($list_cancel))
                        {
                            $condition_cancel = [
                                'order_code IN' =>  $list_cancel
                            ];
                            $set = [
                                'status'    =>  STATUS_CANCEL
                            ];
                            $this->business_quoting->model_quoting->updateAll($set,$condition_cancel);
                            $this->business_quoting->model_order->updateAll($set,$condition_cancel);
                        }


                        $this->Flash->success("Successfully.");
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
        return $this->redirect('/yeni/quoting/');
    }
}
