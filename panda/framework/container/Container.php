<?php

namespace Panda\container;

use Closure;
use Dotenv\Environment\VariablesInterface;
use Panda\database\query\Query;
use Panda\foundation\Application;

class Container
{
    /**
     * 几个重要的概念
     * √1.服务，就是一个实例化的类
     * √2.服务容器，就是一个存放实例化对象的数组
     * √3.服务绑定，就是将一个闭包或者类绑定到实例化对象数组和回调函数数组，按需加载，就是通过匿名函数将这个匿名函数存放到一个数组中去，等需要使用时，执行这个匿名函数即可,通过bind方法，将匿名函数绑定进来
     * √4.单例，就是是否每次都会进行实例化,单例只会实例化一次
     * √5.自动注入，就是实例化一个类的时候，通过反射获取其构造函数的参数，根据参数的类型从而对参数进行实例化,build方法通过反射来实例化某个类
     * √6.自定义依赖参数，在第4个概念中，实例化某些初始化参数，有一些是需要实例化的，有一些是不需要实例化的，所以需要叫自定义依赖参数
     * √7.服务别名，就是外号的意思，我起个外号，通过外号找到这个服务
     * 8.扩展绑定，就是对这个服务的二次加工，当然这个加工可能有好几个，所以可以绑定多个扩展依次进行加工处理
     * √9.上下文绑定，就是在接口绑定实现类的时候，根据不同的类型提示，实现注入不同的接口实现，从而达到上下文绑定的目的
     * √10.以上概念的实现根本都依托于PHP的垃圾回收机制，有兴趣的可以深入研究
     */

    /**
     * application实例化存放到静态变量中去,目的就是随时随地可以调用它进行实例化相关的服务，比如门面等地方
     * @var
     */
    protected static $instance;
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
     * 核心服务的别名数组
     * @var array
     */
    protected $aliasArr = [
        'application' => 'app'
    ];
    /**
     * 需要注册的核心服务
     * @var array
     */
    protected $coreService = [

    ];

    /**
     * 配置文件
     * @var array
     */
    protected static $configs = [];

    /**
     * 获取实例化的容器(存放在静态变量中的)
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * 设置实例化的静态变量
     * @param $instance
     */
    public static function setInstance($instance)
    {
        static::$instance = $instance;
    }

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
        if ($concrete instanceof Closure) {
            $this->closures[$abstract] = $concrete;
        } else {
            $this->binds[$abstract] = $concrete;
        }
        //如果某个类已经实例化过了，那么就重新执行解析
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
    public function instanceByClosure($abstract, $par = [])
    {
        //如果有别名就获取到真实的名称
        $abstract = $this->getNameByAlias($abstract);
        //判断是否重新实例化，单例的话不用重新实例化
        $isNewInstance = $this->isNewInstance($abstract);
        //判断是否有上下文绑定
        $isNeedContext = $this->isNeedContext($abstract);
        //判断是否有实例存在
        $getInstance = $this->getInstance($abstract);
        //如果这个抽象的实例存在，则直接返回
        if ($getInstance && !$isNewInstance && !$isNeedContext) {
            return $getInstance;
        }
        //获取抽象实例
        $concrete = $this->getConcrete($abstract);
        //实例化,如果这个回调函数也有参数呢,所以需要解析
        if ($concrete && $concrete instanceof Closure) {
            $instance = $this->instanceResolveClosure($concrete, $par);
        } else {
            $instance = $this->instanceByReflection($concrete);
        }
        //将实例化类存放到数组
        $this->instances[$abstract] = $instance;

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
    public function isNeedContext($abstract)
    {
        $isInstancing = end($this->isInstancing);
        if ($isInstancing && isset($this->contexts[$isInstancing])) {
            if ($this->contexts[$isInstancing][$abstract]) {
                return $this->contexts[$isInstancing][$abstract];
            }
        }
        return false;
    }

    /**
     * 获取抽象类的实例化
     */
    public function getService($abstract)
    {
        $abstract = $this->getNameByAlias($abstract);
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        return false;
    }

    /**
     * 获取真实的抽象名
     * @param $abstract
     * @return mixed|null
     */
    public function getNameByAlias($abstract)
    {
        return isset($this->aliases[$abstract]) ? $this->aliases[$abstract] : $abstract;
    }

    /**
     * 获取抽象实现回调函数
     * @param $abstract
     * @return mixed
     */
    public function getConcrete($abstract)
    {
        if ($concrete = $this->isNeedContext($abstract)) {
            return $concrete;
        }
        if (isset($this->binds[$abstract])) {
            return $this->binds[$abstract];
        }
        if (isset($this->closures[$abstract])) {
            return $this->closures[$abstract];
        }
        return $abstract;
    }

    /**
     * 解析回调函数
     */
    public function instanceResolveClosure($concrete, $par)
    {
        $reflection = new \ReflectionFunction($concrete);
        $dependencies = [];
        foreach ($reflection->getParameters() as $param) {
            if (isset($par[$param->getName()])) {
                $dependencies[] = $par[$param->getName()];
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
                $dependencies[] = $this->resolveArg($param);
            }
        }
        return $concrete(...$dependencies);
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
     * 解析单个参数
     */
    public function resolveArg($param)
    {
        if (is_object($param)) {
            return $this->instanceByClosure($param->getClass()->name);
        }
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
        $this->aliases[$name] = $abstract;
        return $this;
    }

//    /**
//     * 给服务绑定一个扩展器
//     */
//    public function extenders($abstract, Closure $closure)
//    {
//        if (isset($this->extenders[$abstract])) {
//            $this->instances[$abstract] = $closure($this->instances[$abstract], $this);
//        } else {
//            $this->extenders[$abstract][] = $closure;
//        }
//        return $this;
//    }

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
     * 绑定并且实例化
     */
    public function bindAndInstance($abstract, $concrete = null, $isSingle = false, $arguments = [])
    {
        return $this->bind($abstract, $concrete, $isSingle)->instanceByClosure($abstract, $arguments);
    }

    /**
     *  解析设置的配置参数
     */
    public function resolveConfig()
    {
        $path = $this->instances['path.config'];
        $configFileNames = scandir($path);
        $config = [];
        //处理情况是，如果是一个文件，并且是一个后缀为.php的文件，那么就引入里面的配置文件，而且还要判断是不是返回的数组，如果是则添加到配置文件中
        foreach ($configFileNames as $fileName) {
            $realPath = $path . DIRECTORY_SEPARATOR . $fileName;
            if (is_file($realPath) && pathinfo($realPath)['extension'] == 'php' && is_array($content = include $realPath)) {
                $item[pathinfo($realPath)['filename']] = $content;
                $config = array_merge($config, $item);
            }
        }
        static::setConfig($config);
    }

    /**
     * 设置配置参数
     * @param $config
     */
    public static function setConfig($config)
    {
        static::$configs = $config;
    }

    /**
     * 获取解析后的配置参数
     */
    public static function getConfig()
    {
        return static::$configs;
    }


}