<?php
/**
 * 引入composer的autoload加载机制，实现类的自动加载
 */
require dirname(__DIR__) . '/bootstrap/autoload.php';

$app = new \Panda\foundation\Application(dirname(__DIR__));

///**
// * 测试PDO prepare
// */
//$dbh = new PDO('mysql:host=localhost;dbname=shechunxiao', 'root', '');
//$sql = 'select * from first';
//$statement = $dbh->prepare($sql);
//$statement->execute();
////var_dump($statement->fetchAll());
//
//function wrapValue($value)
//{
//    if ($value === '*') {
//        return $value;
//    }
//    return '`'.str_replace('`', '``', $value).'`';
//}





//$a = 'id';
//function tr($it){
//    return "`".$it."`";
//}
//var_dump(tr($a));die();
//$data = \Panda\facade\Db::table('first')->group('id','name','age')->where('id','>',50)->select();
//$where['id'] = [
//    ['>',1,'and'],
//    ['<',10,'or']
//];
$where['id'] = ['>',1];
$where[] = ['id', '>', 324232];
$where[] = ['name', '=', '张三'];
$where2['test'] = ['>', 100];

$where3['last'] = [
    ['>', 11,'and'],
    ['<', 20, 'or']
];
//->where('id','>',1)
//->where($where)
//where($where2)
//->where($where3)
//$data = \Panda\facade\Db::table('first as f')->field('count(id) as mycount','inter')
//    ->join('first_extend as fe','fe.first_id','=','f.id','left')
//    ->where($where2)
//    ->where($where3)
//    ->whereOr('inner','>',1)
//    ->orders('id','Desc')
//    ->orders('inter','asc')
//    ->group('id','name')
//    ->offset(10)
//    ->limit(1)
//    ->having('id','>',1)
//    ->having('inter','=',1)
//    ->having('name','=',3)
////    ->max('id')
//    ->select();
//var_dump($data);
//$data = \Panda\facade\Db::table('test')->field('id','name')->where('id','>',2)->sum('id');
//$data = \Panda\facade\Db::table('first')->field('id','name')->where('id','>',42)->select();
//$data = \Panda\facade\Db::table('first')->where('id','=',44)->delete();
//$update = [
//    'name'=>'更新数据',
//    'inter'=>32432
//];
$insert = [
    'name'=>'插入数据',
    'inter'=>155
];
$data = \Panda\facade\Db::table('first')->insertGetId($insert);
//$data = \Panda\facade\Db::table('first')->where('id','=',60)->update($update);
var_dump($data);

;
