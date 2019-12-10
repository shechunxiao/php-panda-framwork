<?php

namespace Panda\service;
use Panda\http\Router;

class RouterServiceProvider extends ServiceBase
{
    /**
     * 注册路由相关类
     */
    public function register(){
        $this->registerRouter();
    }

    /**
     *  注册路由类
     */
    public function registerRouter(){
        $this->app->single('router',function($app){
            return new Router($app);
        });
    }




}