<?php

namespace Yeni\Model\Table;

class OrdersTable extends AppTable
{
    protected $condition = [];
    public function initialize(array $config): void
    {
        $this->condition = ['del_flag' => UNDEL];
        $this->setEntityClass(\Yeni\Model\Entity\Orders::class);
    }
}
