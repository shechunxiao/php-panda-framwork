<?php

namespace Panda\common;
use Dotenv\Dotenv;
use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\DotenvFactory;

class Env
{

//$factory = new Dotenv\Environment\DotenvFactory([
//new Dotenv\Environment\Adapter\EnvConstAdapter()
//]);
//$dotenv = Dotenv\Dotenv::create(dirname(__DIR__), null, $factory);
//$dotenv->load();
//
//var_dump(getenv('DB_DATABASE'));
//var_dump($_ENV);

    public function __construct()
    {
        $factory = new DotenvFactory([
            new EnvConstAdapter()
        ]);
        $dotenv = Dotenv::create(dirname(__DIR__), null, $factory);
    }
    public function get(){

    }


}