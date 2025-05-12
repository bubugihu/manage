<?php

namespace Yeni\Library\Business;

use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use Yeni\Model\Table\SetProductTable;

class Report extends Entity
{
    public $model_quoting;
    public $model_product;
    public $model_order;
    public $model_purchasing;
    public $model_pre_purchasing;
    public $model_cost_inventory;
    public $model_cost_incurred;
    public function __construct()
    {
        parent::__construct();
        $this->model_quoting = $this->_getProvider("Yeni.Quoting");
        $this->model_product = $this->_getProvider("Yeni.Product");
        $this->model_order = $this->_getProvider("Yeni.Orders");
        $this->model_purchasing = $this->_getProvider("Yeni.Purchasing");
        $this->model_pre_purchasing = $this->_getProvider("Yeni.PrePurchasing");
        $this->model_cost_inventory = $this->_getProvider("Yeni.CostInventory");
        $this->model_cost_incurred = $this->_getProvider("Yeni.CostIncurred");
    }

    public function getOne($id)
    {
        $condition = [
            'id'    => $id
        ];
        $contain = [
            'Quoting.Product'
        ];
        return $this->model_order->selectOne($condition, $contain);
    }
    public function getList($key_search = [],  $page = 1, $export = false)
    {
        $condition = [
            'OR' => [
                'order_code LIKE' => "%" . $key_search['key_search'] . "%",
                'customer_name LIKE' => "%" . $key_search['key_search'] . "%",
                'customer_phone LIKE' => "%" . $key_search['key_search'] . "%",
            ],
            'YEAR(order_date)' => $key_search['year'],
            'MONTH(order_date)' => $key_search['month'],
            'source' => 1,
        ];
        $order = [
            'status' => "ASC",
            'source'    => "DESC",
            'created_on'    => "DESC",
            'order_date'    => "DESC",
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

    public function getMonthlyYear($current_year)
    {
        $last_year = $current_year -1;
        $last_year_text = "$last_year-12-01";
        $current_year_text = "$current_year-12-01";
        $monthlyOrders = $this->model_order->find()
            ->select([
                'month' => $this->model_order->find()->func()->month(['order_date' => 'literal']),
                'year' => $this->model_order->find()->func()->year(['order_date' => 'literal']),
                'count_order' => $this->model_order->find()->func()->count('id'),
                'sum_price' => $this->model_order->find()->func()->sum('total_actual'),
                'source','status'
            ])
            ->where(['order_date >=' => $last_year_text])
            ->where(['order_date <' => $current_year_text])
            ->where(['status ' => STATUS_QUOTING_DONE])
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
        return [
            12 => 'Dec',
            1=>'Jan',
            2=>'Feb',
            3=>'Mar',
            4=>'Apr',
            5=>'May',
            6=>'Jun',
            7=>'July',
            8=>'Aug',
            9=>'Sep',
            10=>'Oct',
            11=>'Nov'
        ];
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
            $name = $value['name'];
            if(empty($code))
                continue;
            if(in_array($code, $list_product) || in_array($code,array_keys($list_set_product)))
            {
                if(!in_array($code,array_keys($list_set_product)))
                {
                    $sql = "UPDATE product SET `name` = :name, `q_qty` = q_qty + $qty, `q_price` = $price WHERE `code` = '$code'";
                    $connection->execute(
                        $sql,['name' => $name]
                    );
                }else{
                    $list_product_detail = $list_set_product[$code]->set_product_detail;
                    foreach($list_product_detail as $val)
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
                    'name'  => $name,
                    'q_price'   => $price,
                    'q_qty'     => $qty,
                ];
                $new_product = $this->model_product->newEntity($params);
                $this->model_product->save($new_product);
            }

        }//endforeach
    }

    public function getProfit($current_year)
    {
        $last_year = $current_year -1;
        $last_year_text = "$last_year-12-01";
        $current_year_text = "$current_year-12-01";

        $list_expense  = $this->model_cost_incurred->find()
            ->where(['in_date >=' => $last_year_text])
            ->where(['in_date <' => $current_year_text])
            ->toArray();

        $result_ex = [];
        foreach ($list_expense as $value)
        {
            $ex = $value->in_date->format('n');
            $expense = $value->value * $value->quantity;
            if(isset($result_ex[$ex]))
            {
                $result_ex[$ex] += $expense;
            }else{
                $result_ex[$ex] = $expense;
            }
        }

        $list_quoting = $this->model_quoting->find()
            ->where(['q_date >=' => $last_year_text])
            ->where(['q_date <' => $current_year_text])
            ->where(['status' => STATUS_QUOTING_DONE])
            ->contain(['Product'])
            ->toArray();
        $result_monthly = [];
        foreach($list_quoting as $key => $quoting)
        {
            $quoting_monthly = $quoting->q_date->format('n');
            $source = SOURCE[$quoting->source];
            if($quoting->p_price == 0)
            {
                $p_price = floatval($quoting->price/2);
            }else{
                $p_price = floatval($quoting->p_price);
            }
            $profit = (floatval($quoting->price) - $p_price) * intval($quoting->quantity);
            if(isset($result_monthly[$source][$quoting_monthly]))
            {
                $result_monthly[$source][$quoting_monthly]['profit'] += floor($profit);
                $result_monthly[$source][$quoting_monthly]['count_quoting']++;
            }else{
                $result_monthly[$source][$quoting_monthly]['profit'] = floor($profit);
                $result_monthly[$source][$quoting_monthly]['count_quoting'] = 1;
            }
            if(isset($result_monthly['total'][$quoting_monthly]))
            {
                $result_monthly['total'][$quoting_monthly]['profit'] += floor($profit);
                $result_monthly['total'][$quoting_monthly]['count_quoting']++;
            }else{
                $result_monthly['total'][$quoting_monthly]['profit'] = floor($profit);
                $result_monthly['total'][$quoting_monthly]['count_quoting'] = 1;
            }

        }
        if(!empty($result_monthly['total']) && is_array($result_monthly['total']))
        {
            foreach($result_monthly['total'] as $key =>  &$value)
            {
                $expense =  !empty($result_ex[$key]) ? $result_ex[$key] : 0;
                $value['profit'] -= $expense;
                $value['expense'] = $expense;
            }
        }

        return $result_monthly;
    }

    public function delete($order_code)
    {
        $this->model_order->deleteAll(['order_code' => $order_code]);
        $this->model_quoting->deleteAll(['order_code' => $order_code]);
    }

    public function getOutcome($current_year)
    {
        $last_year = $current_year -1;
        $last_year_text = "$last_year-12-01";
        $current_year_text = "$current_year-12-01";
        $list_purchasing = $this->model_purchasing->find()
            ->where(['p_date >=' => $last_year_text])
            ->where(['p_date <' => $current_year_text])
            ->toArray();
        $result_monthly = [];
        foreach($list_purchasing as $key => $purchasing)
        {
            $quoting_monthly = $purchasing->p_date->format('n');
                if(isset($result_monthly[$quoting_monthly]))
                {
                    $result_monthly[$quoting_monthly] += floatval($purchasing->quantity * $purchasing->price);
                }else{
                    $result_monthly[$quoting_monthly] = floatval($purchasing->quantity * $purchasing->price);
                }

        }
        return $result_monthly;
    }

    public function getInventory($current_year)
    {
        $results = [];
        $last_year = $current_year -1;
        $last_year_text = "$last_year-12-01";
        $current_year_text = "$current_year-12-01";

        $query = $this->model_cost_inventory
            ->find()
            ->select(['id', 'month', 'year', 'value', 'del_flag'])
            ->where(['created_on >=' => $last_year_text])
            ->where(['created_on <' => $current_year_text])
            ->where(['del_flag' => UNDEL])->all()->toList()
        ;
        foreach($query as $value)
        {
            $results[$value->month] = $value->value;
        }
        return $results;
    }
}
