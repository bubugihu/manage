<?php

namespace Yeni\Controller;

use Cake\Filesystem\File;
use \Yeni\Controller\AppController;
use \Yeni\Model\Table\ConfigTable;
use \Yeni\Model\Table\SetProductTable;
use Cake\Log\Log;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Yeni\Library\Business\Product;

class ProductController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->business_product = new Product();
        $this->business_set_product = new SetProductTable();
    }

    public function index()
    {
        $arr['key_search'] = $key_search = $_GET['key_search'] ?? "";
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)trim($_GET['page']) : 1;

        $list_products = $this->business_product->getList($key_search, $page);
        $paginate = $this->Common->displayPaginationBelow(LIMIT, $page, $list_products->count(), $arr);

        $this->set('total_products',$list_products->count());
        $this->set('list_products',$list_products->all()->toList());
        $this->set('paginate',$paginate);
    }

    public function create()
    {

    }

    public function setProductList()
    {

    }

    public function createSetProduct()
    {

    }

    public function import()
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
                        $category_id = null;
                        foreach ($dataInput as $key => $value)
                        {
                            if($key <= 6)
                            {
                                continue;
                            }

                            if(empty(trim($value['B'])))
                            {
                                continue;
                            }

                            $results[$key] = $this->business_product->formatValue($value);

                        }
                        if($this->business_product->saveList($results))
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
        return $this->redirect('/yeni/product/');
    }

    public function importSource()
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
                if (!empty($getSheet)) {
                    $dataInput = $getSheet->toArray(null, true, true, true);
//                    $file_path = WWW_ROOT . 'dashboard.po';
//                    $content = 'msgid "Hello"' . PHP_EOL . 'msgstr "Xin chào"';
//                    $file = new File($file_path, true, 0644);
//                    $file->write($content);
//                    $file->close();
                    $filePath = WWW_ROOT . 'Dashboard.po';
                    $fileContent = file_get_contents($filePath);
                    $lines = explode(PHP_EOL, $fileContent);
                    foreach ($dataInput as $key => $value)
                    {
                        if($key <= 1)
                        {
                            continue;
                        }

                        $english = trim($value['A']);
                        $japan = trim($value['B']);

                        foreach ($lines as &$line) {
                            if (substr($line, 0, 6) === 'msgstr') {
                                if (strpos($line, $english) !== false) {
                                    $line = str_replace($english, $japan, $line);
                                    break;
                                }
                            }
                        }
                    }
                    file_put_contents(WWW_ROOT . 'japan.po', implode(PHP_EOL, $lines));

//                    if (count($dataInput) > 0)
//                    {
//                        $results = [];
//                        $category = null;
//                        $name = "";
//                        foreach ($dataInput as $key => $value)
//                        {
//                            if($key <= 14)
//                            {
//                                continue;
//                            }
//
//                            if($key == 868)
//                            {
//                                break;
//                            }
//
//                            if(empty(trim($value['B'])))
//                            {
//                                continue;
//                            }
//
//                            if(!is_numeric(trim($getSheet->getCell('F'.$key)->getValue())))
//                            {
//                                $value['special'] = trim($value['F']);
//                            }
//
//                            if(!is_numeric(trim($getSheet->getCell('G'.$key)->getValue())))
//                            {
//                                $value['G'] = null;
//                            }
//
//                            if(empty(trim($value['A'])))
//                            {
//                                $value['A'] = $category;
//                            }
//
//                            if(empty(trim($value['C'])))
//                            {
//                                $value['C'] = $name;
//                            }
//
//                            $results[$key] = $this->business_product->formatSource($key, $value, $getSheet);
//
//                            if(!empty($results[$key]['category']))
//                            {
//                                $category = $results[$key]['category'];
//                            }
//
//                            if(!empty($results[$key]['name']))
//                            {
//                                $name = $results[$key]['name'];
//                            }
//                        }
//                        if($this->business_product->saveListSource($results))
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
        return $this->redirect('/yeni/product/import-excel/');
    }

    public function importSetProduct()
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
                        $results_detail = [];
                        foreach ($dataInput as $key => $value)
                        {
                            if($key <= 1)
                            {
                                continue;
                            }

                            $this->business_product->formatSetValue($key, $value, $getSheet, $results, $results_detail);
                        }

                        if($this->business_product->saveSetList($results, $results_detail))
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
        return $this->redirect('/yeni/product/set-product-list/');
    }

    public function importExcel()
    {
        $arr['key_search'] = $key_search = $_GET['key_search'] ?? "";
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)trim($_GET['page']) : 1;

        $list_customer = $this->business_product->getListSource($key_search, $page);
        $paginate = $this->Common->displayPaginationBelow(LIMIT, $page, $list_customer->count(), $arr);

        $this->set('list_products',$list_customer->all()->toList());
        $this->set('paginate',$paginate);
    }

    public function deleteSource($code)
    {
        if($this->business_product->deleteSource($code))
        {
            $this->Flash->success("Successfully.");
        }else{
            $this->Flash->error("Failed.");
        }
        return $this->redirect('/yeni/product/import-excel/');
    }

    public function loadingFromSource()
    {
        $list_product_source = $this->business_product->getListSource("", 1, true)->all()->toList();
        $array_code_product = [];
        $list_product = [];
        foreach ($list_product_source as $key => $value)
        {
            $array_product = [];
            $array_product['code'] = $value['code'];
            $array_product['name'] = $value['name'];
            $array_product['p_price'] = $value['p_price'];
            $array_product['note'] = $value['note'];
            $array_product['remark'] = $value['remark'];
            $array_product['description'] = $value['description'];
            $array_product['category'] = $value['category'];
            $array_product['unit'] = $value['unit'];

            if(!in_array($value['code'],$array_code_product))
            {
                $array_code_product[] = $value['code'];
                $list_product[] = $array_product;
            }else{
                $key_exist = array_search($value['code'], $array_code_product);
                $list_product[$key_exist] = $array_product;
            }
        }

        foreach($list_product as $k => $v)
        {
            Log::debug($k . " + " . $v['code']);
        }
        if($this->business_product->saveList($list_product))
        {
            $this->Flash->success("Successfully.");
        }else{
            $this->Flash->error("Failed.");
        }
        return $this->redirect('/yeni/product/');
    }

    public function export($output_type = 'D')
    {
        $config_model = new ConfigTable();
        $config = $config_model->selectList();
        $list_products = $this->business_product->getList("",  1, true);
        $results = $list_products->all()->toList();
        $file_name = "Inventory_" . date('Y-m-d'). ".xlsx";
        $this->set(compact('results', 'output_type', 'file_name', 'config'));
        $this->viewBuilder()->setLayout('xls/default');
        $this->viewBuilder()->setTemplate('export_excel');
        $this->response->withDownload('Inventory_' . date('Y-m-d'). '.xlsx');
        $this->render();

        return;
    }

    public function exportJson()
    {
        $list_products = $this->business_product->getList("",  1, true);
        $list_setProduct = $this->business_set_product->selectList([],['SetProductDetail.Product']);

        $results = [];
        $set = [];

        foreach($list_products as $value)
        {
            $product = [];
            $product['code'] = $value->code;
            $product['name'] = $value->name;
            $product['total'] = $value->total_display;
            $results[$value->code] = $product;
        }

        foreach($list_setProduct as $set_value)
        {
            $set_detail = $set_value->set_product_detail;
            $detail = [];
            foreach($set_detail as $detail_value)
            {
                $detail_product = [];
                $detail_product['code'] = $detail_value->product_code;
                $detail_product['name'] = $detail_value->product->name ?? "";
                $detail_product['total'] = $detail_value->quantity;

                $detail[$detail_value->product_code] = $detail_product;
                $set[$set_value->code] = $detail;
            }
        }

        $results = $results + $set;
        $json_results = json_encode($results);

        $this->set('layout',false);
        $this->response = $this->response
            ->withType('application/json')
            ->withDownload('ProductJson_' . date('Y-m-d'). '.json')
            ->withStringBody($json_results);

        return $this->response;
    }
}
