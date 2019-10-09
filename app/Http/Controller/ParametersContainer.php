<?php
namespace App\Http\Controller;
class ParametersContainer extends InjectionContainer {

    //获取服务
    public function make($name,array $parameters = [])
    {
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
            $instance = $this->build($name,$parameters);
        }
        return $instance;
    }
    //构建一个类，并自动注入
    public function build($class,array $parameters = []){
        $reflector = new \ReflectionClass($class);
        $constructor = $reflector ->getConstructor();
        if (is_null($constructor)){
            //如果没有构造函数，则直接new
            return new $class();
        }
        //参数依赖
        $dependencies = [];
        //获取构造函数所需要的参数
        foreach ($constructor->getParameters() as $dependency){
            //先从自定义参数中获取
            if (isset($parameters[$dependency->getName()])){
                $dependencies[] = $parameters[$dependency->getName()];
                continue;
            }
            if (is_null($dependency->getClass())){
                // 参数类型不是类或接口时，无法从容器中获取依赖
                if ($dependency->isDefaultValueAvailable()){
                    $dependencies[] = $dependency->getDefaultValue();
                }else{
                    throw new \Exception('找不到依赖参数：' . $dependency->getName());
                }
            }else{
                //参数类型是类的话，就make实例化
                $dependencies[] = $this->make($dependency->getClass()->name);
            }
        }
        return $reflector->newInstanceArgs($dependencies);
    }

}