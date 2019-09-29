<?php

require 'bootstrap/autoload.php';
error_reporting(0);
register_shutdown_function( 'appShutdown');
set_error_handler( 'appError',E_ALL ^ E_NOTICE);
set_exception_handler( 'appException');


function appError($a,$b,$c,$d,$e){
    echo 'appError';
    $err = error_get_last();
    var_dump($err);
    var_dump($a);
    var_dump($b);
    var_dump($c);
    var_dump($d);
    var_dump($e);
}
function appException($e2){
    echo 'appException';
    $err = error_get_last();
    var_dump($e2);
    var_dump($err);
    var_dump($e2->getMessage());
}
function appShutdown(){
    echo 'appShutdown';
    $err = error_get_last();
    var_dump($err);
//    $this->appException();
}
//try{
//    require 'tiaoshi.php';
////        $DB = new PDO('mysql:host=127.0.0.1;port=3306;dbname=shechunxiao;charset=UTF8;','root','', [
////        PDO::ATTR_PERSISTENT=>false,
////        PDO::ATTR_CASE=>PDO::CASE_NATURAL,
//////        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT, //静默模式的PDOException报错方式
//////        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, //静默模式的PDOException报错方式
////    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //自己抛出错误的报错模式
//////        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //关联数组
//////        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,  //对象
////        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
////        PDO::ATTR_ORACLE_NULLS => PDO::NULL_TO_STRING,
////        PDO::ATTR_STRINGIFY_FETCHES => false,
////        PDO::ATTR_EMULATE_PREPARES=>false
////    ]);
////    $DB->query('select * from first2');
//
//
//}catch (\Throwable $e){
//    var_dump($e);
//}
//throw new Exception('Uncaught Exception occurred');
//$a = m;
//fdkfjklsdflksdfk();
//$b = new sfsd();
//require 'fdsfsd.txt';
//echo "fddfds';
//for ($i=0 ;  $i<10;$i++){
//
//}
require 'tiaoshi.php';
