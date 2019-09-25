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
        echo $this->message.'什么情况';
        echo $e;
    }
    public function test(){

        echo 'test';

    }
}