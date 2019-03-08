<?php
namespace app\tars;


class TarsStartAlive
{


    public function run()
    {
        $tars = new TarsManage();
        $masterPid = getmypid();
        var_dump($masterPid);
        $tars->keepAlive($masterPid);
    }
}
