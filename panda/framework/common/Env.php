<?php

namespace Panda\common;

use Dotenv\Dotenv;

class Env
{

    public function __construct($basePath)
    {
        $this->dotenv($basePath);
    }

    public function dotenv($basePath)
    {
        $dotenv = Dotenv::create($basePath);
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