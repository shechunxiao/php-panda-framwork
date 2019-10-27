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
     * 单例数组
     * @var array
     */
    protected $isSingles = [];
    /**
     * 正在实例化的类
     * @var array
     */
    protected $isInstancing = [];
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
        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        if ($isSingle) {
            $this->isSingles[$abstract] = true;
        }
        //删除原来的实例化
//        $this->clearInstance($abstract);
        if ($concrete instanceof Closure) {
            $this->closures[$abstract] = $concrete;
        } else {
            $this->binds[$abstract] = $concrete;
        }
        //如果某个类已经实例化过了，那么就重新执行绑定
        if (isset($this->instances[$abstract])) {
            $this->resolve($abstract);
        }
        return $this;
    }

    /**
     * 重新实例化
     * @param $abstract
     */
    public function resolve($abstract)
    {
        $this->instanceByClosure($abstract);
    }


    /**
     * 通过绑定的回调函数实例化类
     */
    public function instanceByClosure($abstract)
    {
        //判断是否重新实例化，单例的话不用重新实例化
        $isNewInstance = $this->isNewInstance($abstract);
        //判断是否有上下文绑定
        $isNeedContext = $this->isNeedContext($abstract);
        //如果这个抽象的实例存在，则直接返回
        if (isset($this->instances[$abstract]) && !$isNewInstance && !$isNeedContext) {
            return $this->instances[$abstract];
        }
        //获取抽象实例
        $concrete = $this->getConcrete($abstract);
        //实例化
        if ($concrete instanceof Closure) {
            $instance = call_user_func($concrete);
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
        if (isset($this->isSingles[$abstract])) {
            return false;
        }
        return true;
    }

    /**
     * 判断是否需要上下文,如果有则返回上下文
     */
    public function isNeedContext($abstract){
        $isInstancing = end($this->isInstancing);
        if ($isInstancing && isset($this->contexts[$isInstancing])){
            if ($this->contexts[$isInstancing][$abstract]){
                return $this->contexts[$isInstancing][$abstract];
            }
        }
    }

    /**
     * 获取抽象实现回调函数
     * @param $abstract
     * @return mixed
     */
    public function getConcrete($abstract)
    {
        if (!($concrete = $this->isNeedContext($abstract))){
            if (isset($this->binds[$abstract])) {
                $concrete = $this->binds[$abstract];
            }
            if (isset($this->closures[$abstract])) {
                $concrete =  $this->closures[$abstract];
            }
        }
        return $concrete;
    }

    /**
     * 通过反射实例化类
     */
    public function instanceByReflection($concrete, $parameters = [])
    {
        $reflection = new \ReflectionClass($concrete);

        //正在实例化的类，用于上下文绑定
        $this->isInstancing[] = $concrete;
        //获取构造函数
        $getConstructor = $reflection->getConstructor();
        if (is_null($getConstructor)) {
            array_pop($this->isInstancing);
            return new $concrete();
        }
        $params = $getConstructor->getParameters();
        $dependencies = []; //需要实例化的依赖项
        foreach ($params as $param) {
            if (isset($parameters[$param->getName()])) {
                $dependencies[] = $param;
                continue;
            }
            if (is_null($param->getClass())) {
                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                } else {
                    echo '缺少参数';
                    die();
                }
            } else {
                $dependencies[] = $param;
            }
        }
        $resolvedInArgs = $this->resolveArgs($dependencies);
        return $reflection->newInstanceArgs($resolvedInArgs);
    }

    /**
     * 解析构造函数依赖项(如果是类就实例化，如果是接口就实例化具体实现)(make中实例化)
     * @param $params
     * @return array
     */
    public function resolveArgs($params)
    {
        $result = [];
        foreach ($params as $param) {
            if (is_object($param)) {
                $result[] = $this->instanceByClosure($param->getClass()->name);
            }
        }
        return $result;
    }

    /**
     * 实例化一次(单例),放在绑定中，绑定的时候就指明实例化一次
     */
    public function single($abstract, $concrete = null, $isSing = true)
    {
        $this->bind($abstract, $concrete, $isSing);
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
    public function extenders($abstract, Closure $closure)
    {
        if (isset($this->extenders[$abstract])) {
            $this->instances[$abstract] = $closure($this->instances[$abstract], $this);
        } else {
            $this->extenders[$abstract][] = $closure;
        }
        return $this;
    }

    /**
     * 给服务绑定上下文绑定
     * @param $when
     * @param $need
     * @param $concrete
     * @return $this
     */
    public function contexts($when, $abstract, $concrete)
    {
        $this->contexts[$when][$abstract] = $concrete;
        return $this;
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