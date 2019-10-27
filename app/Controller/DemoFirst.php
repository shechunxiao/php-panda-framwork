<?php

namespace App\Controller;


class DemoFirst
{
    public $demo;
    public function __construct(Demo $demo)
    {
        $this->demo = $demo;
    }

    public function index()
    {
        echo 'DemoFirst';
    }
    public function demo(){
        $this->demo->index();
    }
}