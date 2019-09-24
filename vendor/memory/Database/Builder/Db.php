<?php
namespace memory\Database\Builder;
class Db {

    public function table($args){
        return $this;
    }
    public function select(){
        echo '选择输出了';
    }
    //thinkphp的实现方式
    public static function __callStatic($name, $arguments)
    {
        echo '调用了';
        // TODO: Implement __callStatic() method.
        echo $name;


    }



}
