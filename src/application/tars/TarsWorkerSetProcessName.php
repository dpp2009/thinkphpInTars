<?php
namespace app\tars;


class TarsWorkerSetProcessName
{

    public function run($info)
    {
        $tars = new TarsManage();
        if( $info['server']->taskworker ){
            \swoole_set_process_name($tars->getTarsName()." tasker");
        }else{
            \swoole_set_process_name($tars->getTarsName()." worker");
        }

    }
}
