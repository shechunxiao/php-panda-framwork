<?php

namespace Panda\http;
use Panda\service\ServiceBase;

class Router extends ServiceBase
{
    /**
     * 注册
     */
    public function register(){
        $this->registerRouter();
    }
}