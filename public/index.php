<?php
/**
 * 引入composer的autoload加载机制，实现类的自动加载
 */
require dirname(__DIR__) . '/bootstrap/autoload.php';

$app = new \Panda\foundation\Application(dirname(__DIR__));

//$data = \Panda\facade\Db::table('first')->group('id','name','age')->where('id','>',50)->select();
$data = \Panda\facade\Db::table('first')->where('id','>',50)->select();
var_dump($data);