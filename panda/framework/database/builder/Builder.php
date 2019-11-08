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
        var_dump($sql);
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
                $sql[$item] = $this->{$item . 'Resolve'}($query->$item, $query);
            }
        }
        return $sql;
    }

    /**
     * 首先判断是否有as，有as则剥离出来再调用escapeValueNoAs
     * @param $value
     * @return string
     */
    public function escapeValue($value)
    {
        if (($pos = $this->posProcessAs($value)) != false) {
            return $pos;
        }
        return $this->escapeValueNoAs($value);
    }

    /**
     * 判断$value中的as的位置
     * @param $value
     * @return bool
     */
    public function posProcessAs($value)
    {
        return strpos($value, 'as');
    }

    /**
     * 转义相应的值
     * @param $value
     * @return string
     */
    public function escapeValueNoAs($value)
    {
        if ($value === '*') {
            return $value;
        }
        return '`' . str_replace('`', '``', $value) . '`';
    }

    /**
     * 解析聚合函数
     * @param $item
     * @return int
     */
    public function aggregateResolve($item, $query)
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
        return 'from ' . $this->escapeValue($item);
    }

    /**
     * 解析所选字段
     * @param $item
     * @return string
     */
    public function fieldsResolve($item)
    {
        foreach ($item as &$field) {
            $field = $this->escapeValue($field);
        }
        $fields = implode(',', $item);
        return 'select ' . $fields;
    }

    /**
     * 解析关联查询
     * @param $item
     * @return string
     */
    public function joinsResolve($item)
    {
        $joinString = '';
        foreach ($item as $join) {
            $joinString .= $join['type'] . ' join ' . $this->escapeValue($join['table']) . ' on ' . $join['first'] . '=' . $join['second'];
        }
        return $joinString;
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