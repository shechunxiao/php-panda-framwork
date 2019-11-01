<?php

namespace Panda\foundation;
use Panda\container\Container;

class Db
{
    /**
     * 构造初始化
     * Db constructor.
     */
    public function __construct(Container $container)
    {
        var_dump($container);
    }
    /**
     * 通过这个魔术方法分发其他方法
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        var_dump($name);
        var_dump($arguments);
        return $this;
    }

}