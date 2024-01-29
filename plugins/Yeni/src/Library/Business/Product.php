<?php

namespace Yeni\Library\Business;

use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Jlpt\Library\Business\Base;

class Product extends Entity
{
    public function __construct()
    {
        parent::__construct();
        $this->model_product = $this->_getProvider("Yeni.Product");
        $this->model_set_product = $this->_getProvider("Yeni.SetProduct");
        $this->model_set_product_detail = $this->_getProvider("Yeni.SetProductDetail");
        $this->model_source_import = $this->_getProvider("Yeni.SourceImport");
        $this->model_cost_incurred= $this->_getProvider("Yeni.CostIncurred");
        $this->set_name = "";
        $this->set_code = "";
        $this->set_avatar = "";
        $this->set_price = 0;
        $this->set_price_option = 0;

    }

    public function getListProduct($condition)
    {
        $list_products = $this->model_product->selectList($condition);
        $list_setProduct = $this->model_set_product->selectList([],['SetProductDetail.Product']);
        $results = [];
        $set = [];

        foreach($list_products as $value)
        {
            $product = [];
            $product['code'] = $value->code;
            $product['name'] = $value->name;
            $product['total'] = $value->total_display;
            $product['q_price'] = $value->q_price;
            $product['p_price'] = $value->p_price;
            $results[$value->code] = $product;
        }

        foreach($list_setProduct as $set_value)
        {
            $set_detail = $set_value->set_product_detail;
            $detail = [];
            foreach($set_detail as $detail_value)
            {
                $detail_product = [];
                $detail_product['code'] = $detail_value->product_code ?? "";
                $detail_product['name'] = $detail_value->product->name ?? "";
                $detail_product['qty'] = $detail_value->quantity ?? 0;
                $detail[$detail_value->product_code] = $detail_product;
                $detail['price'] = $set_value->price;
                $detail['price_option'] = $set_value->price_option;
                $set[$set_value->code] = $detail;
            }
        }
        return $this->formatResultSet($results + $set);
    }
    public function formatResultSet($set)
    {
        $result =  [];
        foreach($set as $key => $value)
        {
            if(isset($value['code']))
            {
                $value['is_set'] = false;
                $result[$value['code']] = $value;
            }else{
                $detail = [];
                $detail['is_set'] = true;
                $detail['code'] = $key;
                $detail['total'] = 0;
                $detail['p_price'] = 0;
                $detail['q_price'] = $value['price'] . "/" . $value['price_option'];
                $detail['name'] = "Set $key (có khung tròn/ không khung tròn)";

                $result[$key] = $detail;
            }
        }
        return $result;
    }
    public function getList($key_search = "",  $page = 1, $export = false)
    {
        $condition = [
            'OR' => [
                'code LIKE' => "%" . $key_search . "%",
                'name LIKE' => "%" . $key_search . "%",
            ]
        ];

        if (strpos($key_search, 'p_qty') !== false) {
            $str = trim($key_search);
            if (strpos($str, '&&') !== false) {
                $array1 = explode("&&", $str);
                $array11 = explode(" ", trim($array1[0]));
                $array12 = explode(" ", trim($array1[1]));
                if(count($array11) == 3 || count($array12) == 3)
                {
                    $condition = [
                        'OR' => [
                            'p_qty ' . $array11[1] => $array11[2],
                            'p_qty ' . $array12[1] => $array12[2],
                        ]
                    ];
                }
            }else{
                $array = explode(" ", $str);
                if(count($array))
                {
                    $condition = [
                        'OR' => [
                            'p_qty ' . $array[1] => $array[2],
                        ]
                    ];
                }
            }
        }

        if (strpos($key_search, 'q_qty') !== false) {
            $str = trim($key_search);
            if (strpos($str, '&&') !== false) {
                $array1 = explode("&&", $str);
                $array11 = explode(" ", trim($array1[0]));
                $array12 = explode(" ", trim($array1[1]));
                if(count($array11) == 3 || count($array12) == 3)
                {
                    $condition = [
                        'OR' => [
                            'q_qty ' . $array11[1] => $array11[2],
                            'q_qty ' . $array12[1] => $array12[2],
                        ]
                    ];
                }
            }else{
                $array = explode(" ", $str);
                if(count($array))
                {
                    $condition = [
                        'OR' => [
                            'q_qty ' . $array[1] => $array[2],
                        ]
                    ];
                }
            }
        }

        $order = [

        ];
        return $this->model_product->getData($page, $condition, [], [], $order, $export);;
    }

    public function getListSource($key_search = "",  $page = 1, $export = false)
    {
        $condition = [
            'OR' => [
                'code LIKE' => "%" . $key_search . "%",
                'name LIKE' => "%" . $key_search . "%",
            ]
        ];

        if (strpos($key_search, 'qty') !== false) {
            $str = trim($key_search);
            if (strpos($str, '&&') !== false) {
                $array1 = explode("&&", $str);
                $array11 = explode(" ", trim($array1[0]));
                $array12 = explode(" ", trim($array1[1]));
                if(count($array11) == 3 || count($array12) == 3)
                {
                    $condition = [
                        'OR' => [
                            'qty ' . $array11[1] => $array11[2],
                            'qty ' . $array12[1] => $array12[2],
                        ]
                    ];
                }
            }else{
                $array = explode(" ", $str);
                if(count($array))
                {
                    $condition = [
                        'OR' => [
                            'qty ' . $array[1] => $array[2],
                        ]
                    ];
                }
            }
        }

        $order = [

        ];
        return $this->model_source_import->getData($page, $condition, [], [], $order, $export);;
    }

    public function saveList($params)
    {
        try{
            if(empty($params))
                return false;
            $list_entities = $this->model_product->newEntities($params);
            $this->model_product->saveMany($list_entities);
        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return false;
        }
        return true;
    }

    public function saveListSource($params)
    {
        try{
            if(empty($params))
                return false;
            $list_entities = $this->model_source_import->newEntities($params);
            $this->model_source_import->saveMany($list_entities);
        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return false;
        }
        return true;
    }

    public function formatValue($params)
    {
        $result = [];
        $result['category_id'] = $this->formatCategory($params['B']);
        $result['code'] = trim($params['C']);
        $result['name'] = trim($params['D']);
        $result['unit_id'] = $this->formatUnit($params['E']);
        return $result;
    }

    public function formatSource($key, $params, $sheet)
    {
        $result = [];
        $result['category'] = $params['A'];
        $result['code'] = trim($params['B']);
        $result['name'] = trim($params['C']);
        $result['remark'] = $params['special'] ?? "";
        $result['note'] = trim($params['I']);
        $result['description'] = trim($params['D']);
        $result['q_price'] = $params['G'];
        $result['p_price'] = trim($sheet->getCell('F'.$key)->getValue());

        return $result;
    }

    public function formatSetValue($key, $params, $sheet, &$results, &$results_detail)
    {
        if(!empty(trim($params['A'])) && !empty(trim($params['C'])) && !empty(trim($params['H'])))
        {
            $this->set_name = trim($params['A']);
            $this->set_code = trim($params['C']);
            $this->set_price = $sheet->getCell('H'.$key)->getValue();
            $formatImage = str_replace('.', '_', $this->set_code);
            $file_name = WWW_ROOT . "img/yeni/set/" . $formatImage .  ".png";
            $results[$key]['code'] = $this->set_code;
            $results[$key]['name'] = $this->set_name;
            $results[$key]['avatar'] = $file_name;
            $results[$key]['price'] = $this->set_price;

            if(!empty(trim($params['I'])))
            {
                $this->set_price_option = $sheet->getCell('I'.$key)->getValue();
                $results[$key]['price_option'] = $sheet->getCell('I'.$key)->getValue();
            }
        }
        $results_detail[$key]['set_product_code'] = $this->set_code;
        $results_detail[$key]['product_code'] = trim($params['E']);
        $results_detail[$key]['quantity'] = intval(trim($params['G']));
    }

    private function formatCategory($category)
    {
        $strLower = mb_strtoupper(trim($category), 'UTF-8');
        $key = array_search($strLower, CATEGORY);
        if ($key !== false) {
            return $key;
        } else {
            return CATEGORY_OTHERS;
        }
    }

    private function formatUnit($unit)
    {
        $strLower = mb_strtoupper(trim($unit), 'UTF-8');
        $key = array_search($strLower, UNIT);
        if ($key !== false) {
            return $key;
        } else {
            return UNIT_O;
        }
    }

    public function saveSetList($results, $results_detail)
    {
        $conection = ConnectionManager::get('default');
        try{
            $conection->begin();
            if(!empty($results))
            {
                $list_entities = $this->model_set_product->newEntities($results);
                $this->model_set_product->saveMany($list_entities);
            }

            if(!empty($results_detail))
            {
                $list_entities = $this->model_set_product_detail->newEntities($results_detail);
                $this->model_set_product_detail->saveMany($list_entities);
            }


        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
            $conection->rollback();
            return false;
        }
        $conection->commit();
        return true;
    }

    public function deleteSource($code)
    {
        try{
            $this->model_source_import->updateAll(['del_flag'=>1], ['code'=>$code]);
            $this->model_product->updateAll(['del_flag'=>1], ['code'=>$code]);
            $this->model_set_product_detail->updateAll(['del_flag'=>1], ['product_code'=>$code]);
        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return false;
        }
        return true;
    }

    public function saveCostIncurred($params)
    {
        try {
            $list_entities = $this->model_cost_incurred->newEntities($params);
            $this->model_cost_incurred->saveMany($list_entities);
            return true;
        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
            dd($e->getMessage());
            return false;
        }
    }
}
