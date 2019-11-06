<?php

namespace Panda\database\connector;

use Panda\container\Container;
use PDO;

class Connect
{
    protected $connect;
    protected $container;
    /**
     * PDO属性
     * @var array
     */
    protected $baseAttribute = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //错误提示规格
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL, //获取结果集时，将null值转换为空字符串
        PDO::ATTR_EMULATE_PREPARES => false, //启用或禁用预处理语句的模拟
        PDO::ATTR_STRINGIFY_FETCHES => false //是否将取出来的数据转换成字符串类型
    ];

    public function __construct()
    {
        $this->container = new Container();
    }

    /**
     * 构建PDO
     *  new PDO('mysql:host=123.4.5.6;dbname=test_db;port=3306','username','password');
     *  new PDO('mysql:host=localhost;dbname=test;charset=utf8', $user, $pass);
     */
    public function newPDO($type, $host, $dbname, $port = 3306, $charset = 'utf8', $username, $password,$attribute)
    {
        try {
            $dns = "$type:host=$host;dbname=$dbname;port=$port;charset=$charset";
            return new PDO($dns, $username, $password,$attribute);
        } catch (\PDOException $e) {
            echo $e->getLine() . '/' . $e->getMessage();die();
        }
    }

    /**
     * 获取PDO对象
     */
    public function connect($attribute = null)
    {
        if (is_null($attribute)){
            $attribute = $this->baseAttribute;
        }
        $args = $this->resolveArgs();
        extract($args,EXTR_SKIP);
        return $this->newPDO($type, $host, $dbname, $port, $charset, $username, $password,$attribute);
    }

    /**
     * 解析pdo连接参数
     */
    public function resolveArgs()
    {
        $config = $this->container->getConfig();
        $database = $config['database'];
        return $database;
    }


}