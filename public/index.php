<?php
/**
 * 引入composer的autoload加载机制，实现类的自动加载
 */
require dirname(__DIR__) . '/bootstrap/autoload.php';

$app = new \Panda\foundation\Application(dirname(__DIR__));
//
//$data = \Panda\facade\Db::table('first')->where('id','=',44)->update([
//    'name'=>'44改变了'
//]);
//var_dump(224332);
//var_dump($data);

//function wrapValue($value)
//{
//    if ($value !== '*') {
//        return '"'.str_replace('"', '""', $value).'"';
//    }
//
//    return $value;
//}
//
//print_r(wrapValue('id'));

//function decimalRemoveZero($args){
//    if($args == ceil($args)){
//        $data = (int)$args;
//    }elseif( 10*$args == ceil(10*$args)){
//        $data = (int)(10*$args)/10;
//    }else{
//        $data = $args;
//    }
//    return $data;
//}
//
//var_dump(decimalRemoveZero(0.25));

 function getDisMoney($money, $discount){
//        $money = '0.01';
//        $discount = '8.5';
    $dis = floor($money * ($discount / 10) *100 ) /100;
    var_dump($dis);
    if($dis == 0){
        $dis = 0.01;
    }
    $dismoney = $money - $dis;
//        dump('应付:'.$dis, '优惠:'.$dismoney);
    return $dismoney;
}

var_dump(getDisMoney(0.20,7));

function getDisMoney2($money, $discount){
//        $money = '0.01';
//        $discount = '8.5';
//    $dis = floor($money * ($discount / 10) *100 ) /100;
    $dis = $money*($discount/10);
    $dis = intval(bcmul($dis,100))/100;
//    $dis = bcmul($dis,100);
var_dump($dis);
    if($dis == 0){
        $dis = 0.01;
    }
    $dismoney = $money - $dis;
//        dump('应付:'.$dis, '优惠:'.$dismoney);
    return $dismoney;
}

var_dump(getDisMoney2(0.20,7));
//var_dump(getDisMoney2(0.22,7));
//var_dump(getDisMoney2(0.28,7));
///
//$a = 0.20;
//$b = 20.8;
//var_dump($b);
//var_dump(intval($b));