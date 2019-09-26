<?php

namespace App\Http\Controller;
class FirstController
{
    public $message;
    public $content;
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function index()
    {
        echo 1;
    }

    public function myException($e)
    {
        $a = $e->getMessage();
        $b = $e->getLine();
        $c = $e->getFile();
        $this->content = $a.'/'.$b.'/'.$c;
        $path = '/test.html';
        ob_start();
        include $path;
        $content = ob_get_clean();
        echo $content.$this->content;
    }
    public function myError(){
        $error = error_get_last();
        echo $error;
        echo '出错了';
    }


    public function test(){
        echo 'test';
    }
}