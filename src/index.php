<?php
// tars 平台然后文件

//读取tars conf配置

//处理合成 src\config\swoole.php配置文件 TODO

$args = $_SERVER['argv'];

$swoft_bin = dirname(__FILE__).DIRECTORY_SEPARATOR.'think';

//php think swoole [start|stop|reload|restart]
$arg_cmd = $args[2]=='start' ? ' swoole start -d' : ' swoole '.$args[2] ;

$cmd = "/usr/bin/php  " . $swoft_bin . $arg_cmd;
//var_dump($cmd);

exec($cmd, $output, $r);









