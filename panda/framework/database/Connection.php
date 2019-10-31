<?php

namespace Panda\database;
class Connection
{
    /**
     * 基础连接属性
     * @var array
     */
    protected $baseAttribute = [
        'PDO::ATTR_CASE' => 'PDO::CASE_NATURAL', //强制列名为指定的大小写
        'PDO::ATTR_ERRMODE' => 'PDO::ERRMODE_EXCEPTION', //错误提示规格
        'PDO::ATTR_ORACLE_NULLS' => 'PDO::NULL_TO_STRING', //获取结果集时，将null值转换为空字符串
        'PDO::ATTR_EMULATE_PREPARES' => false, //启用或禁用预处理语句的模拟
        'PDO::ATTR_STRINGIFY_FETCHES' => false //是否将取出来的数据转换成字符串类型
    ];
    /**
     * 创建PDO连接对象
     */
    public function newPdo(...$arguments)
    {
        var_dump($database);
    }


}