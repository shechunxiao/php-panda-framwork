<?php
/**
 * 引入composer的autoload加载机制，实现类的自动加载
 */
require dirname(__DIR__) . '/bootstrap/autoload.php';

$app = new \Panda\foundation\Application(dirname(__DIR__));

//$data = \Panda\facade\Db::table('first')->group('id','name','age')->where('id','>',50)->select();
//$data = \Panda\facade\Db::table('first')->where('id','>',50)->select();
//var_dump($data);


$where1['id'] = [
         ['>',1,'and'],
         ['<',10,'or']
];
var_dump($where1);
$where2[] = [
          ['id','>',1],
          ['name','=',10],
     ];
var_dump($where2);
$where3[] = ['id','>',1];
var_dump($where3);
