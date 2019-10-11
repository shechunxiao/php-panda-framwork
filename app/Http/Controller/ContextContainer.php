<?php

namespace App\Http\Controller;
use ReflectionClass;
class ContextContainer extends ExtendContainer
{
    //依赖上下文容器
    protected $context = [];
    //构建一个类，并自动注入
    public function build($class, array $parameters = [])
    {
        $reflector = new ReflectionClass($class);
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            // 没有构造函数，直接new
            return new $class();
        }
        $dependencies = [];
        // 获取构造函数所需的参数
        foreach ($constructor->getParameters() as $dependency) {
            if (isset($this->context[$class]) && isset($this->context[$class][$dependency->getName()])){
                //先从上下文中查找
                $dependencies[] = $this->context[$class][$dependency->getName()];
                continue;
            }
            if (isset($parameters[$dependency->getName()])) {
                // 从自定义参数中查找
                $dependencies[] = $parameters[$dependency->getName()];
                continue;
            }

            if (is_null($dependency->getClass())) {
                // 参数类型不是类或接口时，无法从容器中获取依赖
                if ($dependency->isDefaultValueAvailable()) {
                    // 查找默认值，如果有就使用默认值
                    $dependencies[] = $dependency->getDefaultValue();
                } else {
                    // 无法提供类所依赖的参数
                    throw new Exception('找不到依赖参数：' . $dependency->getName());
                }
            } else {
                // 参数类型是一个类时，就用make方法构建该类
                $dependencies[] = $this->make($dependency->getClass()->name);
            }
        }
        return $reflector->newInstanceArgs($dependencies);
    }
    //绑定上下文
    public function addContextualBinding($when,$needs,$give){
        $this->context[$when][$needs] = $give;
    }
    //支持链式方式绑定上下文
    public function when($when){
        return new Context($when,$this);
    }



}