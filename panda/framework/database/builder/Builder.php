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
     * @param $query
     * @return array
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
        if ($this->isProcessAs($value)) {
            return $this->buildAsValue($value);
        }
        return $this->escapeValueNoAs($value);
    }

    /**
     * 重新构建拥有as的值
     * @param $value
     * @return int
     */
    public function buildAsValue($value)
    {
        $split = preg_split('/\sas\s/', $value);
        return $this->escapeValueNoAs($split[0]) . ' as ' . $split[1];
    }

    /**
     * 判断是否有as等
     * @param $value
     * @return bool
     */
    public function isProcessAs($value)
    {
        return strstr($value, 'as') ? true : false;
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
            $joinString .= $join['type'] . ' join ' . $this->escapeValue($join['table']) . ' on ' . $this->pointDeal($join['first']) . '=' . $this->pointDeal($join['second']);
        }
        return $joinString;
    }

    /**
     * 解析表名.字段
     * @param $value
     * @return string
     */
    public function pointDeal($value)
    {
        $value = explode('.', $value);
        return $value[0] .'.'. $this->escapeValueNoAs($value[1]);
    }

    /**
     * 解析where条件
     * @param $item
     */
    public function wheresResolve($item)
    {
        $wheres = [];
        foreach ($item as $value){
            if (is_array($value)){
                foreach ($value as $v){

                }
            }
            $wheres[] = "{$value['where']} {$this->escapeValue($value['field'])}{$value['exp']} ? ";
        }
        var_dump($item);
        var_dump($wheres);
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