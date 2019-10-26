<?php

namespace App\Controller;
use Dotenv\Dotenv;
use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\DotenvFactory;

class TestController implements Demo
{
    public $first;
    public $second;
    public function __construct(FirstController $firstController,$b=4)
    {
        $this->first = $firstController;
        $this->second = $b;
    }

    public function index()
    {
        echo 'app/Controller/Test/index';
    }
}