<?php

namespace Panda\database\connector;

use Panda\container\Container;
use PDO;

class Connect
{
    /**
     * PDO属性
     * @var array
     */
    protected $baseAttribute = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //错误提示规格
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL, //获取结果集时，将null值转换为空字符串
        PDO::ATTR_EMULATE_PREPARES => false, //启用或禁用预处理语句的模拟
        PDO::ATTR_STRINGIFY_FETCHES => false //是否将取出来的数据转换成字符串类型
    ];

    /**
     * 构建PDO
     */
    public function createPDO($dns, $username, $password, $attribute)
    {
        try {
            return new PDO($dns, $username, $password, $attribute);
        } catch (\PDOException $e) {
            echo $e->getLine() . '/' . $e->getMessage();
            die();
        }
    }

    /**
     * 获取PDO对象
     */
    public function connect($dns, $username, $password, $attribute = null)
    {
        if (is_null($attribute)) {
            $attribute = $this->baseAttribute;
        }
        return $this->createPDO($dns, $username, $password, $attribute);
    }


}