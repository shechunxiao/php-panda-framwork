<?php

namespace Panda\foundation;
use Panda\container\Container;

class Application extends Container
{

    /**
     * 初始化
     * Application constructor.
     */
    public function __construct($basePath)
    {

    }

    /**
     * 绑定回调函数到回调数组
     */
    public function bind($abstract,$concrete = null,$isSingle = false){
        return parent::bind($abstract,$concrete,$isSingle);
    }

    /**
     * 实例化类
     */
    public function instance($abstract,$paramters){
        return $this->instanceByClosure($abstract,$paramters);
    }






}