<?php
declare(strict_types=1);

namespace Yeni\Model\Table;

class ConfigTable extends AppTable
{
    protected $condition = [];
    public function initialize(array $config): void
    {
        $this->condition = ['del_flag' => UNDEL];
    }
}
