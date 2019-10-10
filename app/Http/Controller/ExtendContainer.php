<?php
namespace App\Http\Controller;
class ExtendContainer extends AliasContainer{

    /**
     * 扩展器的作用是将绑定的服务容器的情况进行扩展
     */
    //存放扩展器的数组
    protected $extenders = [];
    //给服务绑定扩展器
    public function extend($name,$extender){
        if (isset($this->instances[$name])){
            //已经实例化的服务，直接调用扩展器
            $this->instances[$name] = $extender[$this->instances[$name]];
        }else{
            $this->extenders[$name][] = $extender;
        }
    }
    //获取服务
    public function make($name, array $parameters = [])
    {
        $instance = parent::make($name, $parameters);
        if (isset($this->extenders[$name])){
            //调用扩展器
            foreach ($this->extenders[$name] as $extender){
                $instance = $extender($instance); //这里传入的是一个对象，就不用解析了
            }
        }
        return $instance;
    }


}