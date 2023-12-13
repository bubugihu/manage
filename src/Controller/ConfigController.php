<?php

namespace App\Controller;

use App\Model\Table\ConfigTable;

class ConfigController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->config = new ConfigTable();
    }

    public function index()
    {
        $results = $this->config->selectList();
        $this->set("results",$results);
    }

    public function create()
    {
        foreach($_POST['name'] as $key => $value)
        {
            $this->config->updateAll(
                        [
                            'value' => $_POST['value'][$key],
                            'name' => $value,
                            'unit' => $_POST['unit'][$key],
                            'note' => $_POST['note'][$key],
                        ],
                        ['id'=>$key+1]);
        }
        $this->Flash->success("Successfully");
        return $this->redirect("/config/");
    }
}
