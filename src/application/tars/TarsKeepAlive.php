<?php
namespace app\tars;


class TarsKeepAlive
{


    public function run()
    {
        $tars = new TarsManage();
        $masterPid = $tars->getMasterAlivePid();
        var_dump($masterPid);
        $tars->keepAlive($masterPid);
    }
}
