<?php

namespace Yeni\Controller;

use Yeni\Library\Business\Orders;
use Yeni\Library\Business\Report;

class OrdersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->business_order = new Orders();
    }

    public function index()
    {
        if ($this->request->is('post')) {
            $order_id = $_POST['order_id'];
        }
    }
}
