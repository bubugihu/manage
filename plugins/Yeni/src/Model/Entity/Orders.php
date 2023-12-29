<?php

namespace Yeni\Model\Entity;


use Cake\ORM\Entity;

class Orders extends  Entity
{
    protected $_virtual = [
        'total_display',
    ];
    protected function _getTotalDisplay()
    {
        return number_format( (float)$this->total_order , 0 , '.' , ',' );
    }
}
