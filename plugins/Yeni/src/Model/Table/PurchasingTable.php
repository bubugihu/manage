<?php

namespace Yeni\Model\Table;

class PurchasingTable extends AppTable
{
    protected $condition = [];

    public function initialize(array $config): void
    {

        $this->condition = ['del_flag' => UNDEL];

    }
}
