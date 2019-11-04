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
     *  字段
     * @var
     */
    protected $fields;
    /**
     *  条件
     * @var
     */
    protected $wheres;
    /**
     *  偏移
     * @var
     */
    protected $offset;
    /**
     *  限制数量
     * @var
     */
    protected $limit;

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
     * 聚合函数操作,直接执行相应的查询了
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

    /**
     * 字段
     * @param array $parameter
     * @return $this
     */
    public function field($parameter = [])
    {
        if (is_array($parameter)) {
            $parameter = join(',', $parameter);
        }
        $this->fields = $parameter;
        return $this;
    }

    /**
     * 添加条件
     * @param $field
     * @param $exp
     * @param $value
     */
    public function where($field, $exp, $value)
    {
        if (is_array($field)) {
            if (count($field, COUNT_RECURSIVE) == count($field)) { //如果相等则为一维数组

            } else {//否则为二维数组,多维的不考虑

            }
        }
        $this->wheres[] = [$field, $exp, $value];
    }

    /**
     * 偏移
     * @param int $parameter
     * @return $this
     */
    public function offset($parameter = 0)
    {
        $this->offset = $parameter;
        return $this;
    }

    /**
     * 限制数量
     * @param $parameter
     * @return $this
     */
    public function limit($parameter)
    {
        $this->limit = $parameter;
        return $this;
    }


}