<?php
namespace app\tars;

use think\facade\Env;
use think\facade\Config;

use \Tars\report\ServerFSync;
use \Tars\report\ServerFAsync;
use \Tars\report\ServerInfo;
use \Tars\Utils;
use think\Process;

/**
 *
 */
class TarsManage
{
    public $appName = "";
    public $serverName = "";
    public $tarsName = "";

    public function __construct()
    {
        $root_path = Env::get('root_path');
        $tars_proto_file = dirname($root_path) .DIRECTORY_SEPARATOR.'tars'.DIRECTORY_SEPARATOR.'tars.proto.php';

        if( is_file($tars_proto_file) ){
            //$tars_proto = include ($tars_proto_file);
            $tars_proto = Config::load($tars_proto_file,'tars_proto');

            $this->appName = $tars_proto['appName'];
            $this->serverName = $tars_proto['serverName'];
            $this->tarsName = $this->appName .".". $this->serverName;
        }
    }

    public function getAppName(){
        return $this->appName;
    }
    public function getServerName(){
        return $this->serverName;
    }
    public function getTarsName(){
        return $this->tarsName;
    }

    public function getNodeInfo(){
        $conf = $this->getTarsConf();
        if( !empty($conf) ){
            $node = $conf['tars']['application']['server']['node'];
            $nodeInfo = Utils::parseNodeInfo($node);
            return $nodeInfo;
        }else{
            return [];
        }
    }

    public function getTarsConf(){
	    $root_path = Env::get('root_path');
        $tars_conf = dirname(dirname($root_path)) .DIRECTORY_SEPARATOR.'conf'.DIRECTORY_SEPARATOR.$this->tarsName.'.config.conf';

        if( is_file($tars_conf) ){
            $conf = Utils::parseFile($tars_conf);
            return $conf;
        }else{
            var_dump('get tars_conf file error : '.$tars_conf);
            return [];
        }
    }

    public function keepAlive($masterPid)
    {
        if( $masterPid<1 ){
            return;
        }else{
            $adapter = $this->tarsName.'.objAdapter';
            $application = $this->appName;
            $serverName = $this->serverName;

            $nodeInfo = $this->getNodeInfo();
            if( empty($nodeInfo) ){
                var_dump('keepAlive getNodeInfo fail');
                return null;
            }
            $host = $nodeInfo['host'];
            $port = $nodeInfo['port'];
            $objName = $nodeInfo['objName'];

            $serverInfo = new ServerInfo();
            $serverInfo->adapter = $adapter;
            $serverInfo->application = $application;
            $serverInfo->serverName = $serverName;
            $serverInfo->pid = $masterPid;

            $serverF = new ServerFSync($host, $port, $objName);
            $serverF->keepAlive($serverInfo);

            $adminServerInfo = new ServerInfo();
            $adminServerInfo->adapter = 'AdminAdapter';
            $adminServerInfo->application = $application;
            $adminServerInfo->serverName = $serverName;
            $adminServerInfo->pid = $masterPid;
            $serverF->keepAlive($adminServerInfo);

            var_dump(' keepalive ');
        }
    }

    //判断 worker和master 是否存在，存在则返回masterpid ,不存在返回0 tars会重启服务
    public function getMasterAlivePid()
    {
        $processName = $this->tarsName;
        if( empty($processName) ){
            return 0;
        }else{
            $cmd = "ps aux | grep '" . $processName . " worker' | grep -v grep  | awk '{ print $2}'";
            exec($cmd, $ret);
            if( empty($ret) ) return 0;
            unset($ret);

            $cmd = "ps aux | grep '" . $processName . " master' | grep -v grep  | awk '{ print $2}'";
            exec($cmd, $ret);
            if( empty($ret) ) return 0;

            return $ret[0];
        }
    }
}
