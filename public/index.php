<?php
/**
 * 引入composer的autoload加载机制，实现类的自动加载
 */
require dirname(__DIR__) . '/bootstrap/autoload.php';

$app = new \Panda\foundation\Application(dirname(__DIR__));


//
//$where['id'] = ['>',1];
//$where['name'] = ['>',1];
//$where['age'] = ['>',1];
//$where['test'] = ['>',1];
//var_dump($where);

$wh[] = ['id','=',1];
$wh[] = ['name','=',1];
$wh[] = ['age','=',1];
$wh[] = ['test','=',1];
$wh[] = ['test2','=',1];
//var_dump($wh);

\Panda\facade\Db::table('first as f')
        ->field(['id','name','age'])
        ->joins('second as s','first.id','second.id','left')
        ->joins('three as t','three.id','second.id','right')
        ->joins('four as fo','four.id','second.id')
        ->where($wh)
        ->where('last','=','fsss')
        ->where('last2','like','%fsss')
        ->where('tf','between',[1,2])
        ->where('tf','in',[3,4])
        ->offset(20)
        ->limit(5)
        ->group(['id','name'])
        ->group('id,test')
        ->having('tet','=','12321')
        ->orders('id desc,name asc')
        ->select();