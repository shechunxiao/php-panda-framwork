<?php

namespace App\Controller;
use Dotenv\Dotenv;
use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\DotenvFactory;

class TestController
{

    public function index()
    {
        echo 'app/Controller/Test/index';
    }
}