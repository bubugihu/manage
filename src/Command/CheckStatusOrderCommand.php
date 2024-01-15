<?php

namespace App\Command;

use Cake\Chronos\Date;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;

class CheckStatusOrderCommand extends Command
{
    public function initialize() : void
    {
        parent::initialize();
//        $this->m_week = $this->loadModel('Week');
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
//        $this->updateTableWeek();
        $date = new FrozenTime('now');
        Log::error($date->toDateTimeString());
        $io->out('Done .');
    }

    public function updateTableWeek()
    {
//        $week = $this->m_week->find()->where(['role_leader' => ACTIVE, 'week' => date('W'), 'year' => date('Y')])->first();
//
//        if (!empty($week)){
//            $week->role_leader = INACTIVE;
//            $this->m_week->save($week);
//        }
    }
}
