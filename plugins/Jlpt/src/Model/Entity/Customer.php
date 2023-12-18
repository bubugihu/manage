<?php

namespace Jlpt\Model\Entity;

use Cake\ORM\Entity;

class Customer extends  Entity
{
    protected $_virtual = [
        'full_name_display',
        'birthday_display',
        'gender_display',
        'is_write_display',
        'is_payment_display',
        'is_picture_display',
        'exam_display',
    ];
    protected function _getFullNameDisplay()
    {
        return strtoupper($this->last_name . " " . $this->first_name);
    }

    protected function _getExamDisplay()
    {
        return substr($this->exam, 0, 4) . "/" . substr($this->exam, 4);
    }
    /**
     * Des: Get Birthday
     * @return string
     */
    protected function _getBirthdayDisplay()
    {
        return $this->year . "/" . $this->month . "/" . $this->day;
    }

    protected function _getGenderDisplay()
    {
        if($this->gender == 0)
        {
            return "NAM";
        }
        return "NỮ";
    }


    /**
     * Des: Get is_write
     * @return string
     */
    protected function _getIsWriteDisplay()
    {
        $result = "";
        switch ($this->is_write) {
            case IS_WRITE_NOT_YET:
                $status = IS_WRITE[IS_WRITE_NOT_YET];
                $result = "<span class='badge text-bg-danger'>$status</span>";
                break;
            case IS_WRITE_DONE:
                $status = IS_WRITE[IS_WRITE_DONE];
                $result = "<span class='badge text-bg-primary'>$status</span>";
                break;
        }
        return $result;
    }

    protected function _getIsPaymentDisplay()
    {
        $result = "";
        switch ($this->is_payment) {
            case IS_PAYMENT_WAITING:
                $status = IS_PAYMENT[IS_PAYMENT_WAITING];
                $result = "<span class='badge text-bg-danger'>$status</span>";
                break;
            case IS_PAYMENT_DONE:
                $status = IS_PAYMENT[IS_PAYMENT_DONE];
                $result = "<span class='badge text-bg-primary'>$status</span>";
                break;
        }
        return $result;
    }

    /**
     * Des: Get is_write
     * @return string
     */
    protected function _getIsPictureDisplay()
    {
        $result = "";
        switch ($this->is_picture) {
            case IS_WRITE_NOT_YET:
                $status = IS_WRITE[IS_WRITE_NOT_YET];
                $result = "<span class='badge text-bg-danger'>$status</span>";
                break;
            case IS_WRITE_DONE:
                $status = IS_WRITE[IS_WRITE_DONE];
                $result = "<span class='badge text-bg-primary'>$status</span>";
                break;
        }
        return $result;
    }

    /**
     * Des: Get is_write
     * @return string
     */
    protected function _getIsSendDisplay()
    {
        $result = "";
        switch ($this->is_send) {
            case IS_WRITE_NOT_YET:
                $status = IS_WRITE[IS_WRITE_NOT_YET];
                $result = "<span class='badge text-bg-danger'>$status</span>";
                break;
            case IS_WRITE_DONE:
                $status = IS_WRITE[IS_WRITE_DONE];
                $result = "<span class='badge text-bg-primary'>$status</span>";
                break;
        }
        return $result;
    }
}
