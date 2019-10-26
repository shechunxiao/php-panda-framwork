<?php

namespace Panda\container;
class Container
{
    /**
     * 几个重要的概念
     * 1.服务，就是一个实例化的类
     * 2.服务容器，就是一个存放实例化对象的数组
     * 3.按需加载，就是通过匿名函数将这个匿名函数存放到一个数组中去，等需要使用时，执行这个匿名函数即可,通过bind方法，将匿名函数绑定进来
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
     * 存放回调函数的数组
     * @var array
     */
    protected $closures = [];
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
     * 通过绑定的回调函数实例化类
     */
    public function instanceByClosure()
    {

    }

    /**
     * 通过反射实例化类
     */
    public function instanceByReflection()
    {

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
    public function bindToAlias()
    {

    }

    /**
     * 给服务绑定一个扩展器
     */
    public function bindToExtenders()
    {

    }

    /**
     * 给服务绑定上下文绑定
     */
    public function bindToContexts()
    {

    }


}