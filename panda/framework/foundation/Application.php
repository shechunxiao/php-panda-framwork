<?php

namespace Panda\foundation;

use Panda\container\Container;
use Panda\service\RouterServiceProvider;
use ReflectionException;

class Application extends Container
{
    /**
     * panda框架的基本目录
     * @var
     */
    protected $basePath;
    /**
     * 服务提供者
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * 初始化
     * Application constructor.
     * @param $basePath
     */
    public function __construct($basePath)
    {
        //路径绑定
        $this->instancePath($basePath);
        //注册自身的实例化
        $this->instanceSelf();
        //实例化一些核心的类的别名
        $this->bindCoreAlias();
        //注册核心的服务
        $this->instanceCore();
    }

    /**
     * 注册基本的路径
     * @param $basePath
     * @return $this
     */
    public function instancePath($basePath)
    {
        $this->basePath = rtrim($basePath, "\/");
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
        Container::setInstance($this);

        $this->instances['app'] = $this;

        $this->instances[Container::class] = $this;
    }

    /**
     * 绑定一些核心服务的别名
     */
    public function bindCoreAlias()
    {
        foreach ($this->aliasArr as $name => $abstract) {
            $this->alias($abstract, $name);
        }
    }

    /**
     * 注册一些核心的服务提供者，我们这不需要延迟加载，所以就不考虑了
     */
    public function instanceCore()
    {
        //注册日志

        //注册路由
        $this->register(new RouterServiceProvider($this));


//        //注册env
//        $this->instances['env'] = new Env($this->instances['path']);
//        //注册config里面的配置文件
//        $this->resolveConfig();
//        //注册所有门面对应的服务
//        $this->instanceCoreService();
    }

    /**
     * 绑定回调函数到回调数组
     * @param $abstract
     * @param null $concrete
     * @param bool $isSingle
     * @return Container
     * @throws ReflectionException
     */
    public function bind($abstract, $concrete = null, $isSingle = false)
    {
        return parent::bind($abstract, $concrete, $isSingle);
    }

    /**
     * 实例化类
     * @param $abstract
     * @param array $parameters
     * @return mixed|object
     * @throws ReflectionException
     */
    public function instance($abstract, $parameters = [])
    {
        return $this->instanceByClosure($abstract, $parameters);
    }

    /**
     * 实例化核心服务类
     */
    public function instanceCoreService()
    {
        foreach ($this->coreService as $abstract => $concrete) {
            if (!is_string($abstract)) {
                $abstract = $concrete;
            }
            $this->bindAndInstance($abstract, $concrete);
        }
    }

    /**
     *  注册服务提供者
     * @param $service
     * @param bool $force
     * @return bool
     */
    public function register($service, $force = false)
    {
        if ($serviceProvider = $this->getServiceProvider($service) && !$force) {
            return $serviceProvider;
        }
        if (method_exists($service, 'register')) {
            $service->register();
        }
        return $service;
    }

    /**
     *  获取服务
     * @param $service
     * @return bool|mixed
     */
    public function getServiceProvider($service)
    {
        return in_array($service,$this->serviceProviders) ? $service : false;
    }

}