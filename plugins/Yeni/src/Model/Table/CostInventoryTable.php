<?php

namespace Yeni\Model\Table;

class CostInventoryTable extends AppTable
{
    public function initialize(array $config): void
    {
        $this->condition = ['del_flag' => UNDEL];
    }
}
