<?php

namespace Panda\common;

use Dotenv\Dotenv;
use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\DotenvFactory;
use Panda\container\Container;

class Env
{

    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->dotenv();

    }

    public function dotenv()
    {
        $dotenv = Dotenv::create(dirname(__DIR__));
        $dotenv->load();
    }

    public function get($name)
    {
        return getenv($name);
    }

    public function all($name)
    {
        return $_ENV;
    }


}