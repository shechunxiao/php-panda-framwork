<?php

namespace Panda\database\query;

use Panda\database\builder\Builder;
use Panda\database\connector\Connect;

class Query
{
    /**
     * 某一类型的连接器对象，比如MysqlConnect
     * @var
     */
    protected $connector;
    /**
     * 构建sql的类
     * @var Builder
     */
    protected $builder;
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
     * 关联查询
     * @var
     */
    protected $joins;
    /**
     *  条件
     * @var
     */
    protected $wheres = [];
    /**
     * 分组
     * @var array
     */
    protected $groups;
    /**
     * having条件
     * @var
     */
    protected $havings = [];
    /**
     * 排序
     * @var
     */
    protected $orders;
    /**
     *  限制数量
     * @var
     */
    protected $limit;
    /**
     *  偏移
     * @var
     */
    protected $offset;


    /**
     * 构造函数
     * Query constructor.
     */
    public function __construct($connector)
    {
        $this->connector = $connector;
        $this->builder = new Builder();
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
     * @return $this
     */
    public function where($field, $exp = '', $value = '')
    {
        if (is_array($field)) { //直接就规定只能是二维数组
            $this->wheres = array_merge($this->wheres, $field);
        } else {
            $this->wheres[] = [$field, $exp, $value];
        }
        return $this;
    }

    /**
     * 排序
     * @param $parameter
     * @return $this
     */
    public function orders($parameter)
    {
        if (is_array($parameter)) {
            $this->orders = join(',', $parameter);
        } else {
            $this->orders = $parameter;
        }
        return $this;
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

    /**
     * 关联查询
     * @param $table (关联的表)
     * @param $onFirst
     * @param $onSecond
     * @param string $type (关联类型)
     * @return $this
     */
    public function joins($table, $onFirst, $onSecond, $type = '')
    {
        $this->joins[] = [$table, $onFirst, $onSecond, $type];
        return $this;
    }

    /**
     * 分组group by
     * @param $parameter
     * @return $this
     */
    public function group($parameter)
    {
        if (is_array($parameter)) {
            $this->groups = join(',', $parameter);
        } else {
            $this->groups = $parameter;
        }
        return $this;
    }

    /**
     * having条件
     */
    public function having($field, $exp = '', $value = '')
    {
        if (is_array($field)) { //直接就规定只能是二维数组
            $this->havings = array_merge($this->wheres, $field);
        } else {
            $this->havings[] = [$field, $exp, $value];
        }
        return $this;
    }

    /**
     * 查询全部
     */
    public function select()
    {

    }

    /**
     * 查询一条语句（limit 1）
     */
    public function first()
    {

    }

    /**
     * 更新
     */
    public function update()
    {

    }

    /**
     * 删除
     */
    public function delete()
    {

    }

}