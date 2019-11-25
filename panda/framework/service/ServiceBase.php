<?php

namespace Panda\service;

use Panda\foundation\Application;

class ServiceBase
{
    /**
     * 实例化application
     * @var
     */
    protected $app;

    /**
     * ServiceBase constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

}