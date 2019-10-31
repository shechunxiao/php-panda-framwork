<?php

namespace Panda\database;
class Connection
{
    /**
     * 基础连接属性
     * @var array
     */
    protected $baseAttribute = [
        PDO::ATTR_CASE=>PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS=>PDO::NULL_TO_STRING,
        PDO::ATTR_EMULATE_PREPARES=>false,
        PDO::ATTR_STRINGIFY_FETCHES=>false
    ];

    /**
     * 创建PDO连接对象
     */
    public function newPdo(){

    }




}