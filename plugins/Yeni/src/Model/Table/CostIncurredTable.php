<?php

namespace Yeni\Model\Table;

class CostIncurredTable extends AppTable
{
    protected $condition = [];

    public function initialize(array $config): void
    {

        $this->condition = ['del_flag' => UNDEL];

    }
}
