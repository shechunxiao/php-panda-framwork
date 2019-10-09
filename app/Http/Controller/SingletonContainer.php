<?php

namespace App\Http\Controller;

use Closure;

class SingletonContainer extends DeferContainer
{

    //绑定服务
    public function bind($name, $instance = [], $shared = false)
    {
        if ($instance instanceof Closure){
            // 如果$instance是一个回调函数，就绑定到bindings。
            $this->bindings[$name] = [
                'callback'=>$instance,
                // 标记是否单例
                'shared'=>$shared
            ];
        }else{
            $this->instances[$name] = $this->make($name);
        }
    }
    //绑定一个单例，所谓单例就是只实例化一次
    public function singleton($name,$instance){
        $this->bind($name,$instance,true);
    }
    //获取服务，这里有一个缺陷就是如果不是匿名函数的make有问题
    public function make($name){
        if (isset($this->instances[$name])){
            return $this->instances[$name];
        }
        if (isset($this->bindings[$name])){
            $instance = call_user_func($this->bindings[$name]['callback']);
            //执行回调函数
            if ($this->bindings[$name]['shared']){
                //标记为单例时，存储到服务中
                $this->instances[$name] = $instance;
            }
        }else{
            $instance = new $name();
        }
        return $instance;
    }


}