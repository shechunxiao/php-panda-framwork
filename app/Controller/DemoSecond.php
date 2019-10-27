<?php

namespace App\Controller;


class DemoSecond
{
    public $demo;

    public function __construct(Demo $demo)
    {
        $this->demo = $demo;
    }

    public function index()
    {
        echo 'DemoSecond';
    }
    public function demo(){
        $this->demo->index();
    }
}