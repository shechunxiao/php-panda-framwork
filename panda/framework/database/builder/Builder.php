<?php

namespace Panda\database\builder;

use http\Env\Request;
use Panda\database\query\Query;

class Builder
{

    public $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=',
        'like', 'like binary', 'not like', 'between', 'ilike',
        '&', '|', '^', '<<', '>>',
        'rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];
    /**
     * 拼接sql的顺序
     * @var array
     */
    protected $sqlOrders = [
        'aggregate',
        'fields',
        'table',
        'joins',
        'wheres',
        'groups',
        'havings',
        'orders',
        'limit',
        'offset',
    ];

    /**
     * 查询sql
     * @param Query $query
     * @return string
     */
    public function sqlForSelect(Query $query)
    {
        $sql = $this->dealSqlOrders($query);
//        return 'select';
    }

    /**
     * 更新sql
     */
    public function sqlForUpdate()
    {

    }

    /**
     * 增加sql
     */
    public function sqlForInsert()
    {

    }

    /**
     * 删除sql
     */
    public function sqlForDelete()
    {

    }

    /**
     * 根据sql排序处理
     */
    public function dealSqlOrders($query)
    {
        $sql = [];
        foreach ($this->sqlOrders as $key => $item) {
            if (!is_null($query->$item)) {
                $sql[$item] = $this->{$item . 'Resolve'}($query->$item);
            }
        }
    }

    /**
     * 解析聚合函数
     * @param $item
     * @return int
     */
    public function aggregateResolve($item)
    {
        return 111;
    }

    /**
     * 解析table
     * @param $item
     * @return string
     */
    public function tableResolve($item)
    {
        return 'from ' . $item;
    }

    /**
     * 解析所选字段
     * @param $item
     */
    public function fieldsResolve($item)
    {

    }

    /**
     * 解析关联查询
     * @param $item
     */
    public function joinsResolve($item)
    {

    }

    /**
     * 解析where条件
     * @param $item
     */
    public function wheresResolve($item)
    {

    }

    /**
     * 解析groups
     * @param $item
     */
    public function groupsResolve($item)
    {

    }

    /**
     * 解析having条件
     * @param $item
     */
    public function havingsResolve($item)
    {

    }

    /**
     * 解析排序
     * @param $item
     */
    public function ordersResolve($item)
    {

    }

    /**
     * 解析limit
     * @param $item
     */
    public function limitResolve($item)
    {

    }

    /**
     * 解析offset
     * @param $item
     */
    public function offsetResolve($item)
    {

    }


}