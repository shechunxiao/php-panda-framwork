<?php

namespace App\Http\Controller;
class FirstController
{
    public $message;
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
        echo $e->getMessage();
        echo '<br>';
        echo $e->getLine();
    }
    public function test(){

        echo 'test';

    }
}