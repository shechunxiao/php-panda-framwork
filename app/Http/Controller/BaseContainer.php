<?php
namespace App\Http\Controller;
class BaseContainer{

    //已绑定的服务
    protected $instances = [];
    //绑定服务
    public function instance($name,$instance){
        $this->instances[$name] = $instance;
    }
    //获取服务
    public function get($name){
        return isset($this->instances[$name])? $this->instances[$name] :null;
    }



}