<?php
namespace App\Http\Controller;

use Closure;
class DeferContainer extends BaseContainer{

    //已绑定的回调函数
    protected $bindings = [];
    //绑定服务
    public function bind($name,$instance = []){
        if ($instance instanceof Closure){
            $this->bindings[$name] = $instance;
        }else{
            $this->instances[$name] = $this->make($name);
        }
    }
    //获取服务
    public function make($name){
        if (isset($this->instances[$name])){
            return $this->instances[$name];
        }
        if (isset($this->bindings[$name])){
            $instance = call_user_func($this->bindings[$name]);
        }else{
            $instance = new $name();
        }
        return $instance;
    }




}