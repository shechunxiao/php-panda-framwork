<?php
/**
 * 引入composer的autoload加载机制，实现类的自动加载
 */
require dirname(__DIR__) . '/bootstrap/autoload.php';

$app = new \Panda\foundation\Application(dirname(__DIR__));

//研究路由的实现以及Exception报错和日志系统的实现方式
