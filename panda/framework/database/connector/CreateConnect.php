<?php
namespace Panda\database\connector;

use Panda\database\query\Query;

class CreateConnect{

    /**
     * 实例化的连接
     * @var
     */
    protected $connection;
    /**
     * 创建连接对象,比如如果是mysql就实例化mysql驱动对象。
     */
    public function connect($type){
        //第一步实例化type类型对应的类
        $class = __NAMESPACE__.'\\'.ucfirst($type).'Connect';
        $connector = new $class();
        //第二步实例化query类
        $query = new Query($connector);
        return $query;
    }
















}