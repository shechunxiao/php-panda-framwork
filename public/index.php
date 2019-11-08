<?php
/**
 * 引入composer的autoload加载机制，实现类的自动加载
 */
require dirname(__DIR__) . '/bootstrap/autoload.php';

$app = new \Panda\foundation\Application(dirname(__DIR__));

//$data = \Panda\facade\Db::table('first')->group('id','name','age')->where('id','>',50)->select();
//$where['id'] = [
//    ['>',1,'and'],
//    ['<',10,'or']
//];
//$where['id'] = ['>',1];
$where[] = ['id', '>', 324232];
$where[] = ['name', '=', '张三'];
$where2['test'] = ['>', 100];

$where3['last'] = [
    ['>', 11],
    ['<', 20, 'or']
];
//->where('id','>',1)
//->where($where)
//where($where2)
//->where($where3)
$data = \Panda\facade\Db::table('first')->where($where2)->where($where3)->whereOr('inner','>',1);
//var_dump($data);


//var_dump($where1);
//$where3[] = ['id','>',1];
//var_dump($where3);
