<?php
/**
 * 引入composer的autoload加载机制，实现类的自动加载
 */
require dirname(__DIR__) . '/bootstrap/autoload.php';

$app = new \Panda\foundation\Application(dirname(__DIR__));
//$app->bind(\App\Controller\Demo::class,function(){
//   return new \App\Controller\TestController();
//});
//$app->alias(\App\Controller\TestController::class,'test')->alias(\App\Controller\TestController::class,'fdfd')->bind(\App\Controller\TestController::class);
//$get = $app->getInstance(\App\Controller\TestController::class);
//var_dump($get);

$instance = $app->instanceByReflection(\App\Controller\TestController::class);
var_dump($instance);