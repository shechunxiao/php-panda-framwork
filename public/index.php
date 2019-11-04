<?php
/**
 * 引入composer的autoload加载机制，实现类的自动加载
 */
require dirname(__DIR__) . '/bootstrap/autoload.php';

$app = new \Panda\foundation\Application(dirname(__DIR__));

\Panda\facade\Db::table('first')->table('fdsfds')->field(['id','name','age'])->offset(20)->limit(5);