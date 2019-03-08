<?php
namespace app\tars;


class TarsManagerSetProcessName
{

    public function run($server)
    {
        $tars = new TarsManage();
        \swoole_set_process_name($tars->getTarsName()." manager");
    }
}
