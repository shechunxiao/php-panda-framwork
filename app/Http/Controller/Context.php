<?php

namespace App\Http\Controller;

class Context
{
    /**
     * 上下文绑定,这个意思就是不同的应用场景下，注入不同的接口实现
     * @var
     */
    protected $when;
    protected $needs;
    protected $container;

    public function __construct($when, ContextContainer $container)
    {
        $this->when = $when;
        $this->container = $container;
    }

    public function needs($needs)
    {
        $this->needs = $needs;
        return $this;
    }

    public function give($give)
    {
        //调用容器绑定依赖的上下文
        $this->container->addContextualBinding($this->when,$this->needs,$give);
    }
}