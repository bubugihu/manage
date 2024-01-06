<?php

namespace Yeni\Library\Business;

use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use Yeni\Model\Table\SetProductTable;

class Report extends Entity
{
    public function __construct()
    {
        parent::__construct();
        $this->model_quoting = $this->_getProvider("Yeni.Quoting");
        $this->model_product = $this->_getProvider("Yeni.Product");
        $this->model_order = $this->_getProvider("Yeni.Orders");
    }

    public function getList($key_search = "",  $page = 1, $export = false)
    {
        $condition = [
            'OR' => [
                'order_code LIKE' => "%" . $key_search . "%",
                'customer_name LIKE' => "%" . $key_search . "%",
                'customer_phone LIKE' => "%" . $key_search . "%",
            ],
        ];

        $order = [

        ];
        return $this->model_order->getData($page, $condition, [], [], $order, $export);
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

    public function formatValueOrder($key, $params, $sheet)
    {
        $result = [];
        $result['order_code'] = trim($params['A']);
        $result['order_date'] = new FrozenTime(trim($params['B']));
        $result['customer_name'] = trim($params['C']);
        $result['customer_phone'] = trim($params['D']);
        $result['customer_addr'] = trim($params['E']);
//        $result['shipping'] = trim($params['F']);
//        $result['total_order'] = trim($sheet->getCell('G'.$key)->getValue());
        $result['source'] = 1;
        $result['note'] = trim($params['H']);
        return $result;
    }

    public function getMonthlyYear($year)
    {
        $monthlyOrders = $this->model_order->find()
            ->select([
                'month' => $this->model_order->find()->func()->month(['order_date' => 'literal']),
                'year' => $this->model_order->find()->func()->year(['order_date' => 'literal']),
                'count_order' => $this->model_order->find()->func()->count('id'),
                'sum_price' => $this->model_order->find()->func()->sum('total_order'),
                'source'
            ])
            ->where(['YEAR(order_date)' => $year])
            ->group(['source', 'month'])
            ->order(['source', 'month'])
            ->toArray();

        return $this->formatGetMonthlyYear($monthlyOrders);
    }

    private function formatGetMonthlyYear($monthlyOrders)
    {
        $result = [];
        foreach($monthlyOrders as $value)
        {
            if($value->source == 0) //shopee
            {
                for($i = 1;$i<=12;$i++)
                {
                    if($value->month == $i)
                    {
                        $result['shopee'][$value->month] = $value;
                    }
                }
            }else if($value->source == 1) // zalo
            {
                for($i = 1;$i<=12;$i++)
                {
                    if($value->month == $i)
                    {
                        $result['zalo'][$value->month] = $value;
                    }
                }
            }
        }

        for($i = 1;$i<=12;$i++)
        {
            $count_order = 0;
            $sum_price = 0;
            if(!empty($result['zalo'][$i]))
            {
                $count_order += $result['zalo'][$i]->count_order;
                $sum_price += $result['zalo'][$i]->sum_price;
            }

            if(!empty($result['shopee'][$i]))
            {
                $count_order += $result['shopee'][$i]->count_order;
                $sum_price += $result['shopee'][$i]->sum_price;
            }

            if($count_order > 0)
            {
                $result['total'][$i]['count_order'] = $count_order;
                $result['total'][$i]['sum_price'] = $sum_price;
            }
        }

        return $result;
    }

    public function matchingNumber()
    {
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $result = [];
        $index = 1;
        foreach($labels as $value)
        {
            $result[$index] = $value;
            $index++;
        }

        return array_reverse($result);
    }

    public function confirm($order_code)
    {
        $connection = ConnectionManager::get('default');
        try{
            //update order
            $connection->begin();
            $field = ['status' => STATUS_QUOTING_DONE];
            $where = ['order_code' => $order_code];
            $this->model_order->updateAll($field,$where);
            //update quoting
            $this->model_quoting->updateAll($field,$where);
            $this->updateInventory($order_code);
            $connection->commit();
            return true;
        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
            $connection->rollback();
            return false;
        }

    }

    public function updateInventory($order_code)
    {
        $list_entities = $this->model_quoting->selectList(['order_code' => $order_code]);
        $set_product_model = new SetProductTable();
        $list_set_product = $set_product_model->find('list', [
            'fields' => ['id', 'code','del_flag'],
            'conditions' => ['SetProduct.del_flag' => UNDEL],
            'keyField' => 'code',
            'valueField' => function($value) {
                return $value;
            },
        ])->contain(['SetProductDetail'])->toArray();
        //update inventory
        $list_product = $this->model_product->find('list', [
            'fields' => ['id', 'code','del_flag'],
            'conditions' => ['Product.del_flag' => UNDEL],
            'keyField' => 'id',
            'valueField' => function($value) {
                return $value['code'];
            },
        ])->toArray();

        $connection = ConnectionManager::get('default');
        foreach($list_entities as $value)
        {
            $qty = $value['quantity'];
            $code = $value['code'];
            $price = $value['price'];
            if(empty($code))
                continue;
            if(in_array($code, $list_product))
            {
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
            }else{
                $params = [
                    'code'  => $code,
                    'name'  => 'mã mới chưa import',
                    'p_price'   => $price,
                    'p_qty'     => $qty,
                ];
                $new_product = $this->model_product->newEntity($params);
                $this->model_product->save($new_product);
            }

        }//endforeach
    }
}
