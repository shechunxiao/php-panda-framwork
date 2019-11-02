<?php

namespace Panda\foundation;
use Panda\container\Container;

class Db
{
    protected $container;
    /**
     * 构造初始化
     * Db constructor.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * 连接
     */
    public function connection(){

    }
    /**
     * 通过这个魔术方法分发其他方法
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($method, $arguments)
    {
        $connection = $this->connection();
        $connection->$method(...$arguments);
        return $this;
    }

}