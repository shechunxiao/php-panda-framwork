<?php

namespace App\Http\Controller;
use http\Env\Request;

class SecondController
{
    use FirstTrait;
    public $a;
    public $b;
    const c = 3;
    const d = 4;
//    public function __construct(...$data)
//    {
//        echo 'data........';
//        var_dump($data);
//        echo 'data........';
//        $this->a = $data[0];
//        $this->b = $data[1];
//    }

    public function __construct($m=22,$n=33,FirstController $first)
    {
        echo 'construct初始化';
    }

    /**
     * 这里是主要函数
     */
    public function index(){
        echo 'SecondController/index';
    }
    /**
     * test方法
     */
    public function test(){
        echo 'SecondController/test';
    }


}