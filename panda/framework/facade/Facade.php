<?php

namespace Panda\facade;
use Panda\container\Container;
use Panda\database\query\Query;

class Facade
{
    /**
     * 已经实例化的门面
     * @var array
     */
    protected static $resolveFacade;

    /**
     * 获取门面对应的服务实例化
     */
    public static function getFacadeInstance(){
        //之所以用static调用，是因为如果有一个子类，那么static调用的是子类的方法，如果没有子类就是该父类的方法
        $class = static::getFacadeClass();
        if (isset(static::$resolveFacade[$class])){
            return static::$resolveFacade[$class];
        }
        $container =  Container::getInstance();
        $instance = $container->instanceByClosure("Panda\\foundation\\".$class);
        return $instance;
    }

    public static function getFacadeClass(){
        return 'panda/framework/facade/Facade.php:子类没有重写父类该方法';
    }

    /**
     * 静态调用方法
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($method, $arguments)
    {
        $instance = static::getFacadeInstance();
        return call_user_func_array(array($instance,$method),$arguments);
    }

}