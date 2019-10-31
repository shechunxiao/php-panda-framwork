<?php

namespace Panda\database\mysql;

use Panda\database\Connection;

class MysqlConnection extends Connection
{
    /**
     * mysql单独配置属性
     * @var array
     */
    protected $attribute = [
        'PDO::ATTR_DEFAULT_FETCH_MODE'=>'PDO::FETCH_ASSOC', //默认结果集为关联数组
        'PDO::ATTR_PERSISTENT'=>false //请求一个持久连接，而非创建一个新连接
    ];
    /**
     * mysql连接属性
     * @var array
     */
    protected $connect = [

    ];
    public function __construct()
    {
        $this->setConnectArgs();
    }

    /**
     * 获取连接参数
     */
    public function setConnectArgs(){
        $pdo = $this->newPdo(['name'=>1,'age'=>2]);

    }

}