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

//$result = \Panda\facade\Db::table('first as f')
//        ->field(['id','name','age'])
//        ->joins('second as s','first.id','second.id','left')
//        ->joins('three as t','three.id','second.id','right')
//        ->joins('four as fo','four.id','second.id')
//        ->where($wh)
//        ->where('last','=','fsss')
//        ->where('last2','like','%fsss')
//        ->where('tf','between',[1,2])
//        ->where('tf','in',[3,4])
//        ->offset(20)
//        ->limit(5)
//        ->group(['id','name'])
//        ->group('id,test')
//        ->having('tet','=','12321')
//        ->orders('id desc,name asc')
//        ->select()
//        ;
//var_dump($result);

$data = \Panda\facade\Db::table('first')->field('id,name,inter')->where('id','>',35)->select();
var_dump($data);


//$dbh = new PDO('mysql:host=localhost;dbname=shechunxiao', 'root', '',[
//    PDO::ATTR_CASE => PDO::CASE_NATURAL,
//    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //错误提示规格
//    PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL, //获取结果集时，将null值转换为空字符串
//    PDO::ATTR_EMULATE_PREPARES => false, //启用或禁用预处理语句的模拟
//    PDO::ATTR_STRINGIFY_FETCHES => false //是否将取出来的数据转换成字符串类型
//]);
//$result = $dbh->query('select * from first where id > 1');
//var_dump($result->fetchAll());
//var_dump($dbh);