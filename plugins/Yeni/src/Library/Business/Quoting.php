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
                    'order_code LIKE' => "%" . $key_search . "%",
                ],
            ];
            $order = [
                'quantity' => "DESC"
            ];
        }else{
            $condition = [
                'OR' => [
                    'code LIKE' => "%" . $key_search . "%",
                    'order_code LIKE' => "%" . $key_search . "%",
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

    public function saveListImportShopee($params, $list_set_product)
    {
        $connection = ConnectionManager::get('default');
        try{
            $connection->begin();
            if(empty($params))
                return false;
            //quotinng
            if($params['quoting'])
            $list_entities = $this->model_quoting->newEntities($params['quoting']);
            $this->model_quoting->saveMany($list_entities);

            $list_order = $this->model_order->newEntities($params['order']);
            $this->model_order->saveMany($list_order);

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

    public function formatValueQuotingShopee($key, $params, $sheet, $order_code, $list_product)
    {
        $result_quoting['order_code'] = "SHOPEE_" . ($params['A']);
        $result_quoting['code'] = ($params['S']);
        $result_quoting['quantity'] = ($params['Z']);
        if($params['D'] == "Đang giao")
        {
            $result_quoting['status'] = STATUS_QUOTING_PROCESS;
        }else if($params['D'] == "Hoàn thành")
        {
            $result_quoting['status'] = STATUS_QUOTING_DONE;
        }else{
            $result_quoting['status'] = STATUS_QUOTING_CANCEL;
        }

        $q_date = ($sheet->getCell('C'.$key)->getValue());
        $result_quoting['q_date'] = new FrozenTime($q_date);
        $result_quoting['price'] = ($sheet->getCell('Y'.$key)->getValue()); //
        $result_quoting['source'] = SOURCE_SHOPEE;
        $result_quoting['p_price'] = $list_product[($params['S'])] ?? 0;

        return $result_quoting;
    }
    public function formatValueOrderShopee($key, $params, $sheet, &$order_code)
    {
        $result_order = [];
        if($order_code != $params['A'])
        {
            $order_code = $params['A'];
            $result_order['order_code'] = "SHOPEE_" . $order_code;
            $result_order['total_order'] = floatval(($sheet->getCell('AC'.$key)->getValue())) - floatval(($sheet->getCell('AD'.$key)->getValue()));
            $result_order['total_actual'] = $result_order['total_order'] - floatval(($sheet->getCell('AT'.$key)->getValue())) - floatval(($sheet->getCell('AU'.$key)->getValue())) - floatval(($sheet->getCell('AV'.$key)->getValue()));
            $q_date = ($sheet->getCell('C'.$key)->getValue());
            $result_order['order_date'] = new FrozenTime($q_date);
            $result_order['shipping'] = 0;
            $result_order['source'] = SOURCE_SHOPEE;
            $result_order['customer_name'] = "SHOPEE CUSTOMER NAME";
            $result_order['status'] = STATUS_QUOTING_DONE;
        }

        return $result_order;
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
