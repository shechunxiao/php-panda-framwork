<?php

namespace Panda\container;

use Closure;

class Container
{
    /**
     * 几个重要的概念
     * 1.服务，就是一个实例化的类
     * 2.服务容器，就是一个存放实例化对象的数组
     * 3.服务绑定，就是将一个闭包或者类绑定到实例化对象数组和回调函数数组，按需加载，就是通过匿名函数将这个匿名函数存放到一个数组中去，等需要使用时，执行这个匿名函数即可,通过bind方法，将匿名函数绑定进来
     * 4.单例，就是是否每次都会进行实例化,单例只会实例化一次
     * 5.自动注入，就是实例化一个类的时候，通过反射获取其构造函数的参数，根据参数的类型从而对参数进行实例化,build方法通过反射来实例化某个类
     * 6.自定义依赖参数，在第4个概念中，实例化某些初始化参数，有一些是需要实例化的，有一些是不需要实例化的，所以需要叫自定义依赖参数
     * 7.服务别名，就是外号的意思，我起个外号，通过外号找到这个服务
     * 8.扩展绑定，就是对这个服务的二次加工，当然这个加工可能有好几个，所以可以绑定多个扩展依次进行加工处理
     * 9.上下文绑定，就是在接口绑定实现类的时候，根据不同的类型提示，实现注入不同的接口实现，从而达到上下文绑定的目的
     * 10.以上概念的实现根本都依托于PHP的垃圾回收机制，有兴趣的可以深入研究
     */
    /**
     * 存放实例化对象的数组
     * @var array
     */
    protected $instances = [];
    /**
     * 存放回调函数的绑定
     * @var array
     */
    protected $closures = [];
    /**
     * 存放类名的绑定
     */
    protected $binds = [];
    /**
     * 服务的别名数组
     * @var array
     */
    protected $aliases = [];
    /**
     * 扩展绑定数组
     * @var array
     */
    protected $extenders = [];
    /**
     * 上下文绑定数组
     * @var array
     */
    protected $contexts = [];

    /**
     * 绑定服务(如果是回调就绑定到回调数组，如果是对象就绑定到实例化数组)
     */
    public function bind($abstract, $concrete = null, $isSingle = false)
    {
        //删除原来的实例化
        $this->clearInstance($abstract);
        if ($concrete instanceof Closure) {
            $this->closures[$abstract]['concrete'] = $concrete;
            $this->closures[$abstract]['isSingle'] = $isSingle;
        }
        $this->binds[] = $abstract;
    }

    /**
     * 通过绑定的回调函数实例化类
     */
    public function instanceByClosure($abstract)
    {
        //判断是否重建，单例的话不用重建
        $isNewInstance = $this->isNewInstance($abstract);
        //如果这个抽象的实例存在，则直接返回
        if (isset($this->instances[$abstract]) && !$isNewInstance) {
            return $this->instances[$abstract];
        }
        //获取抽象实例
        $concrete = $this->getConcrete($abstract);
        //实例化
        if ($concrete && $concrete['concrete'] instanceof Closure) {
            $instance = call_user_func($concrete['concrete']);
            $this->instances[$abstract] = $instance;
        } else {
            $instance = $this->instanceByReflection($concrete);
        }
        return $instance;
    }

    /**
     * 判断是否重新实例化
     */
    public function isNewInstance($abstract)
    {
        if (isset($this->binds[$abstract]) && $this->binds[$abstract]['isSingle']) {
            return false;
        }
        return true;
    }

    /**
     * 获取抽象实现回调函数
     * @param $abstract
     * @return mixed
     */
    public function getConcrete($abstract)
    {
        if (isset($this->binds[$abstract])) {
            return $this->binds[$abstract];
        }
    }

    /**
     * 通过反射实例化类
     */
    public function instanceByReflection($concrete)
    {
        $reflection = new \ReflectionClass($concrete);
        var_dump($reflection);
//        return $this;
    }

    /**
     * 实例化一次(单例),放在绑定中，绑定的时候就指明实例化一次
     */
    public function single($abstract, $concrete = null, $isSing = true)
    {
        $this->bind($abstract, $concrete, $isSing);
    }

    /**
     * 绑定回调函数到回调数组
     */
    public function bindToClosures()
    {

    }

    /**
     * 给服务绑定一个别名
     */
    public function alias($abstract, $name)
    {
        $this->aliases[$abstract][] = $name;
        return $this;
    }

    /**
     * 给服务绑定一个扩展器
     */
    public function extenders()
    {

    }

    /**
     * 给服务绑定上下文绑定
     */
    public function contexts()
    {

    }

    /**
     * 清除原来的实例化
     */
    public function clearInstance($abstract)
    {
        if (isset($this->instances[$abstract])) {
            unset($this->instances[$abstract]);
        }
    }

    /**
     * 获取实例化的类
     */
    public function getInstance($name)
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }
    }


}