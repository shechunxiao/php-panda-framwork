<?php

namespace Panda\database\query;

use Panda\database\connector\Connect;

class Query
{
    /**
     * 某一类型的连接器对象，比如MysqlConnect
     * @var
     */
    protected $connector;
    /**
     * 用于操作的表名
     * @var
     */
    protected $table;
    /**
     * 聚合函数
     * @var
     */
    protected $aggregate;


    /**
     * 构造函数
     * Query constructor.
     */
    public function __construct($connector)
    {
        $this->connector = $connector;
    }

    /**
     * 表名
     * @return $this
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * 聚合函数操作
     * @param $operate
     * @param null $parameter
     * @return $this
     */
    public function aggregate($operate, $parameter = null)
    {
        $this->aggregate['operate'] = $operate;
        $this->aggregate['parameter'] = $parameter;
        return $this;
    }

    /**
     * 统计
     * @param null $parameter
     * @return $this
     */
    public function count($parameter = null)
    {
        return $this->aggregate('count', $parameter);
    }

    /**
     * 最大
     * @param null $parameter
     * @return $this
     */
    public function max($parameter = null)
    {
        return $this->aggregate('max', $parameter);
    }

    /**
     * 最小
     * @param null $parameter
     * @return $this
     */
    public function min($parameter = null)
    {
        return $this->aggregate('min', $parameter);
    }

    /**
     * 平均
     * @param null $parameter
     * @return $this
     */
    public function avg($parameter = null)
    {
        return $this->aggregate('avg', $parameter);
    }

    /**
     * 求和
     * @param null $parameter
     * @return $this
     */
    public function sum($parameter = null)
    {
        return $this->aggregate('sum', $parameter);
    }


}