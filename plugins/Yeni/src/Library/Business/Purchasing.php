<?php

namespace Yeni\Library\Business;

use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use Jlpt\Library\Business\Base;


class Purchasing extends Entity
{
    public function __construct()
    {
        parent::__construct();
        $this->model_quoting = $this->_getProvider("Yeni.Quoting");
        $this->model_product = $this->_getProvider("Yeni.Product");
        $this->model_order = $this->_getProvider("Yeni.Orders");
        $this->model_purchasing = $this->_getProvider("Yeni.Purchasing");
        $this->model_pre_purchasing = $this->_getProvider("Yeni.PrePurchasing");
    }

    public function selectListQuoting($condition)
    {
        return $this->model_quoting->selectList($condition);
    }
    public function getList($key_search = "",  $page = 1, $export = false, $type = null)
    {
        $order = [];
        if(is_null($type))
        {
            $condition = [
                'OR' => [
                    'code LIKE' => "%" . $key_search . "%",
                ],
            ];
            $order = [
                'quantity' => "DESC"
            ];
        }else{
            $condition = [
                'OR' => [
                    'code LIKE' => "%" . $key_search . "%",
                ],
            ];
        }

        return $this->model_purchasing->getData($page, $condition, [], [], $order, $export);
    }

    public function saveList($params)
    {
        $connection = ConnectionManager::get('default');
        $sql = "";
        try{
            $connection->begin();
            if(empty($params))
                return false;
            $list_entities = $this->model_purchasing->newEntities($params);
            $this->model_purchasing->saveMany($list_entities);

            $list_product = $this->model_product->find('list', [
                'fields' => ['id', 'code', 'total_qty','del_flag'],
                'conditions' => ['Product.del_flag' => UNDEL],
                'keyField' => 'code',
                'valueField' => function($value) {
                    return $value['total_qty'];
                },
            ])->toArray();
            //update inventory
            $list_add = [];
            foreach($list_entities as $value)
            {
                $qty = $value['quantity'];
                $code = $value['code'];
                $price = $value['price'];
                $name = $value['name'];
                if(empty($code))
                    continue;
                if(in_array($code, array_keys($list_product)))
                {
                    $sql = "UPDATE product SET `name` = :name,`p_qty` = p_qty + $qty, `p_price` = $price WHERE `code` = '$code'";
                    $connection->execute(
                        $sql,['name' => $name]
                    );
//                    if(intval($list_product[$code]) > 0)
//                    {
//                        $pre = $this->model_pre_purchasing->newEntity($value->toArray());
//                        $this->model_pre_purchasing->save($pre);
//                        $this->model_purchasing->updateAll(['del_flag' => DEL_FLAG], ['id' => $value['id']]);
//
//                    }else{
//                        $sql = "UPDATE product SET `name` = :name,`p_qty` = p_qty + $qty, `p_price` = $price WHERE `code` = '$code'";
//                        $connection->execute(
//                            $sql,['name' => $name]
//                        );
//                    }
                }else{
                    $params = [
                        'code'  => $code,
                        'name'  => $name,
                        'p_price'   => $price,
                        'p_qty'     => $qty,
                    ];
                    if(!in_array($code, $list_add))
                    {
                        $list_add[] = $code;
                        $new_product = $this->model_product->newEntity($params);
                        $this->model_product->save($new_product);
                    }

                }
            }
            $connection->commit();
        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
            $connection->rollback();
            return false;
        }
        return true;
    }

    public function saveListOrder($params)
    {
        $connection = ConnectionManager::get('default');
        try{
            $connection->begin();

            foreach($params as $value)
            {
                $where = ['order_code' => $value['order_code']];
                unset($value['order_code']);
                $this->model_order->updateAll($value,$where);
            }


            $connection->commit();
        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
            $connection->rollback();
            return false;
        }
        return true;
    }
    public function formatValue($key, $params, $sheet)
    {
        $result = [];
        $result['code'] = ($params['B']);
        $result['name'] = ($params['C']);
        $result['quantity'] = intval(($params['E']));
        $result['status'] = STATUS_QUOTING_DONE;
        $result['p_date'] = FrozenTime::now()->subDays(3);
        $result['price'] = floatval(($sheet->getCell('F'.$key)->getValue()));
        $result['source'] = 0;
        return $result;
    }

    private function formatStatus($status)
    {
        $strLower = ($status);
        $key = array_search($strLower, STATUS_QUOTING);
        if ($key !== false) {
            return $key;
        } else {
            return 0;
        }
    }

    public function formatValueZalo($key, $params, $sheet, $order_code)
    {
        $result = [];
        if(!empty($order_code))
        {
            $result['order_code'] = $order_code;
        }
        $result['code'] = trim($params['G']);
        $result['quantity'] = trim($params['I']);
        $result['status'] = STATUS_QUOTING_DONE;
        $result['q_date'] = new FrozenTime('now');
        $result['note'] = "";
        $result['price'] = (floatval($params['J']) / floatval($result['quantity'])) * 1000;
        $result['source'] = 1;
        return $result;
    }

    public function formatValueOrder($key, $params, $sheet)
    {
        $result = [];
        $result['order_code'] = trim($params['A']);
        $result['order_date'] = new FrozenTime(trim($params['B']));
        $result['customer_name'] = trim($params['C']);
        $result['customer_phone'] = trim($params['D']);
        $result['customer_addr'] = trim($params['E']);
        $result['shipping'] = floatval(trim($sheet->getCell('F'.$key)->getValue())) * 1000;
        $result['total_order'] = floatval(trim($sheet->getCell('G'.$key)->getValue())) * 1000;
        $result['source'] = 1;
        $result['note'] = trim($params['H']);
        return $result;
    }

    public function updateByOrderCode($fields, $where)
    {
        $this->model_order->updateAll($fields, $where);
    }

    public function createNewOrder($params)
    {
        $new = $this->model_order->newEntity($params);
        $this->model_order->save($new);
    }

    public function updatePPrice($key, $params, $sheet)
    {
        $set = [
            'p_price'   =>   floatval(trim($sheet->getCell('F'.$key)->getValue()))
        ];
        $where = [
            'code'  => trim($params['B']),
        ];
        $this->model_product->updateAll($set, $where);
    }
}
