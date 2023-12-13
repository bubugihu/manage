<?php

namespace App\Library\Business;

use Cake\Chronos\Date;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;

class Quoting extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->model_quoting = $this->_getProvider("Quoting");
        $this->model_product = $this->_getProvider("Product");
    }

    public function getList($key_search = "",  $page, $export = false)
    {
        $condition = [
            'OR' => [
                'code LIKE' => "%" . $key_search . "%",
            ]
        ];

        $order = [

        ];
        return $this->model_quoting->getData($page, $condition, [], [], $order, $export);;
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

    public function formatValue($key, $params, $sheet)
    {
        $result = [];
        $result['code'] = trim($params['S']);
        $result['quantity'] = trim($params['Z']);
        $result['source'] = 0;
        $result['status'] = $this->formatStatus($params['D']);
        $q_date = trim($sheet->getCell('C'.$key)->getValue());
        $result['q_date'] = new FrozenTime($q_date);
        $result['note'] = trim($params['E']);
        $result['price'] = trim($sheet->getCell('Y'.$key)->getValue());
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
}
