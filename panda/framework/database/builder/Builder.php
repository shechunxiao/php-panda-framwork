<?php

namespace Panda\database\builder;

class Builder
{
    /**
     * query实例化对象
     * @var
     */
    protected $query;
    /**
     * 拼接sql的顺序
     * @var array
     */
    protected $sqlOrders = [
        'aggregate',
        'fields',
        'from',
        'joins',
        'wheres',
        'groups',
        'havings',
        'orders',
        'limit',
        'offset',
    ];

    /**
     * 获取最终的sql语句
     * @param $query
     * @param $type
     */
    public function getSql($query, $type)
    {
        $this->query = $query;
        switch (strtolower($type)) {
            case 'select':
                $sql = $this->select();
                break;
            case 'first':
                $sql = $this->first();
                break;
            case 'update':
                $sql = $this->update();
                break;
            case 'delete':
                $sql = $this->delete();
                break;
            case 'insert':
                $sql = $this->insert();
                break;
        }
        return $sql;
    }

    /**
     * 获取select的对应语句
     */
    public function select()
    {

    }

    /**
     * 获取first的对应语句
     */
    public function first()
    {

    }

    /**
     * 获取update的对应语句
     */
    public function update()
    {

    }

    /**
     * 获取delete的对应语句
     */
    public function delete()
    {

    }

    /**
     * 获取insert的对应语句
     */
    public function insert()
    {

    }

    /**
     * 解析聚合函数
     */
    public function resolveAggregate()
    {

    }

    /**
     * 解析join连接
     */
    public function resolveJoins()
    {

    }

    /**
     * 解析wheres
     */
    public function resolveWheres()
    {

    }

    /**
     * 解析havings
     */
    public function resolveHavings()
    {

    }

}