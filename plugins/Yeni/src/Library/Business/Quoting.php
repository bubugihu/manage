<?php

namespace Yeni\Library\Business;

use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use Jlpt\Library\Business\Base;

class Quoting extends Entity
{
    public function __construct()
    {
        parent::__construct();
        $this->model_quoting = $this->_getProvider("Yeni.Quoting");
        $this->model_product = $this->_getProvider("Yeni.Product");
        $this->model_order = $this->_getProvider("Yeni.Orders");
    }

    public function getList($key_search = "",  $page, $export = false, $type)
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
                'source' => $type
            ];
        }

        return $this->model_quoting->getData($page, $condition, [], [], $order, $export);
    }

    public function saveList($params, $list_set_product)
    {
        $connection = ConnectionManager::get('default');
        try{
            $connection->begin();
            if(empty($params))
                return false;
            $list_entities = $this->model_quoting->newEntities($params);
            $this->model_quoting->saveMany($list_entities);

            //update inventory
            foreach($list_entities as $value)
            {
                $qty = $value['quantity'];
                $code = $value['code'];
                $price = $value['price'];
                if(empty($code))
                    continue;
                if(!in_array($code,array_keys($list_set_product)))
                {
                    $sql = "UPDATE product SET `q_qty` = q_qty + $qty, `q_price` = $price WHERE `code` = '$code'";
                    $connection->execute(
                        $sql,
                    );
                }else{
                    $list_product = $list_set_product[$code]->set_product_detail;
                    foreach($list_product as $val)
                    {
                        $qty_set_detail = $val['quantity'];
                        $code_set_detail = $val['product_code'];

                        $sql = "UPDATE product SET `q_qty` = q_qty + $qty_set_detail WHERE `code` = '$code_set_detail'";
                        $connection->execute(
                            $sql,
                        );
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
        $result['code'] = trim($params['S']);
        $result['quantity'] = trim($params['Z']);
        $result['status'] = $this->formatStatus($params['D']);
        $q_date = trim($sheet->getCell('C'.$key)->getValue());
        $result['q_date'] = new FrozenTime($q_date);
        $result['note'] = trim($params['E']);
        $result['price'] = trim($sheet->getCell('Y'.$key)->getValue());
        $result['source'] = 0;
        return $result;
    }

    private function formatStatus($status)
    {
        $strLower = trim($status);
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
}
