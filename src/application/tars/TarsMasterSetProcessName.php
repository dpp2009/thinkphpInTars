<?php
namespace app\tars;

use think\facade\Env;

class TarsMasterSetProcessName
{

    public function run($swoole)
    {
        $tars = new TarsManage();
        \swoole_set_process_name($tars->getTarsName()." master");
    }
}
