<?php

namespace Yeni\Library\Business;

use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use Yeni\Model\Table\SetProductTable;

class Incurred extends Entity
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

    public function getList($key_search = [],  $page = 1, $export = false)
    {
        $condition = [
            'OR' => [
                'name LIKE' => "%" . $key_search['key_search'] . "%",
            ],
            'YEAR(in_date)' => $key_search['year'],
            'MONTH(in_date)' => $key_search['month'],
        ];

        return $this->model_cost_incurred->getData($page, $condition, [], [], [], false);
    }
}
