<?php

namespace App\Http\Controller;
use ReflectionClass;
class InjectionContainer extends SingletonContainer
{
    //获取服务,解决之前的问题
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
            //使用build方法构建此类
            $instance = $this->build($name);
        }
        return $instance;
    }
    //构建一个类，并且自动注入服务（即参数依赖注入）
    public function build($class){
        $reflector  = new ReflectionClass($class);
        $constructor = $reflector ->getConstructor();
        if (is_null($constructor)){
            //如果没有构造函数，则直接new
            return new $class();
        }
        //参数为类的依赖
        $dependencies = [];
        //获取构造函数所需要的参数
        foreach ($constructor->getParameters() as $dependency){
            if (is_null($dependency->getClass())){
                // 无法提供类所依赖的参数
                throw new \Exception('找不到依赖参数：' . $dependency->getName());
            }else{
                //参数类型是类的话，就make实例化
                $dependencies[] = $this->make($dependency->getClass()->name);
            }
        }
        return $reflector->newInstanceArgs($dependencies);
    }



}