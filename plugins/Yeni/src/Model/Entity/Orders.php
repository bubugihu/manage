<?php

namespace Yeni\Model\Entity;


use Cake\ORM\Entity;

class Orders extends  Entity
{
    protected $_virtual = [
        'total_display',
        'status_display',
    ];
    protected function _getTotalDisplay()
    {
        return number_format( (float)$this->total_order , 0 , '.' , ',' );
    }
    protected function _getStatusDisplay()
    {
        $result = "";
        switch ($this->status) {
            case STATUS_NEW:
                $status = STATUS[$this->status];
                $result = "<span class='badge text-bg-warning'>$status</span>";
                break;

            case STATUS_PROCESS:
                $status = STATUS[$this->status];
                $result = "<span class='badge text-bg-primary'>$status</span>";
                break;
            case STATUS_DONE:
                $status = STATUS[$this->status];
                $result = "<span class='badge text-bg-success'>$status</span>";
                break;
            case STATUS_CANCEL:
                $status = STATUS[$this->status];
                $result = "<span class='badge text-bg-danger'>$status</span>";
                break;
        }
        return $result;
    }
}
