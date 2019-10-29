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
        //基础路径绑定
        $this->instanceBasePath($basePath);
        //注册自身的实例化
        $this->instanceSelf();
        //实例化一些核心的类的别名
        $this->aliasBase();
    }

    /**
     * 注册基本的路径
     * @param $basePath
     * @return $this
     */
    public function instanceBasePath($basePath)
    {
        $basePath = rtrim($basePath, "\/");
        $this->instances['path'] = $basePath;
        $this->instances['path.app'] = $basePath . DIRECTORY_SEPARATOR . 'app';
        $this->instances['path.config'] = $basePath . DIRECTORY_SEPARATOR . 'config';
        return $this;
    }

    /**
     * 注册自身
     */
    public function instanceSelf()
    {
        $this->instances['app'] = $this;
        $this->instances[Container::class] = $this;
    }

    /**
     * 一些核心的服务添加别名
     */
    public function aliasBase(){

    }

    /**
     * 绑定回调函数到回调数组
     */
    public function bind($abstract, $concrete = null, $isSingle = false)
    {
        return parent::bind($abstract, $concrete, $isSingle);
    }

    /**
     * 实例化类
     */
    public function instance($abstract, $paramters = [])
    {
        return $this->instanceByClosure($abstract, $paramters);
    }


}