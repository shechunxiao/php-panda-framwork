<?php

namespace panda\database\mysql;
use Panda\database\Connection;

class MysqlConnection extends Connection
{
    /**
     * mysql独有配置属性
     * @var array
     */
    protected $attribute = [

    ];
    /**
     * mysql连接属性
     * @var array
     */
    protected $connect = [
        
    ];
    public function __construct()
    {

    }
}