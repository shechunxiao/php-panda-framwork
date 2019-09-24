<?php

namespace memory\Facades;
class Db
{

    /**
     * 静态调用
     * @param $method
     * @param $args
     */
    public static function __callStatic($method, $args)
    {
        //laravel实现门面的方式
//        $db = new \memory\Database\Builder\Db();
//        return $db->$method($args);
        //thinkphp实现门面的方式是,对于db数据库而言，thinkphp没有对Db实现静态调用的门面封装，可能是考虑到了查询的问题吧。
        return call_user_func([new \memory\Database\Builder\Db(),$method],$args);
    }
    public function ceshi(){
        echo '测试';
    }
}