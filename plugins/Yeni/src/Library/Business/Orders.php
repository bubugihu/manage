<?php

namespace Yeni\Library\Business;

use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use Yeni\Model\Table\SetProductTable;

class Orders extends Entity
{
    public function __construct()
    {
        parent::__construct();
        $this->model_quoting = $this->_getProvider("Yeni.Quoting");
        $this->model_product = $this->_getProvider("Yeni.Product");
        $this->model_order = $this->_getProvider("Yeni.Orders");
    }

    public function createOrderZalo($data)
    {
        try{
            $set_product_model = new SetProductTable();
            $list_set_product = $set_product_model->find('list', [
                'fields' => ['id', 'code','del_flag'],
                'conditions' => ['SetProduct.del_flag' => UNDEL],
                'keyField' => 'code',
                'valueField' => function($value) {
                    return $value;
                },
            ])->contain(['SetProductDetail'])->toArray();
            $param_order = [
                'order_code' => $data[0],
                'customer_name' => $data[1],
                'customer_phone' => $data[2],
                'customer_addr' => $data[3],
                'total_order' => $data[4],
                'note' => $data[5],
                'shipping' => $data[6],
                'total_actual' => $data[11],
                'source'    => 1, // zalo
            ];
            $new = $this->model_order->newEntity($param_order);
            $this->model_order->save($new);
            return true;
        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function createQuotingZalo($data)
    {
        $connection = ConnectionManager::get('default');
        try{
            $connection->begin();
            if(empty($data) || empty($data[7]))
                return false;
            $result = [];
            foreach($data[7] as $key => $value)
            {
                $quoting['code'] = $data[7][$key];
                $quoting['quantity'] = floatval($data[8][$key]);
                $quoting['price'] = floatval($data[9][$key]);
                $quoting['status'] = STATUS_QUOTING_NEW;
                $quoting['q_date'] = new FrozenTime('now');
                $quoting['source'] = 1; // zalo
                $quoting['order_code'] = $data[0]; // zalo
                $result[] = $quoting;
            }
            $list_entities = $this->model_quoting->newEntities($result);
            $this->model_quoting->saveMany($list_entities);

            $connection->commit();
            return true;
        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
            $connection->rollback();
            return false;
        }
    }

    public function updateInventory($list_entities, $list_set_product)
    {
        //update inventory
        try{
            $connection = ConnectionManager::get('default');
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
            }//endforeach
            return true;
        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return false;
        }

    }
}
