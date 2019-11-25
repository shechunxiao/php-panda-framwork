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
        unset($sql['aggregate']);
        if (!empty($sql)) {
            return $this->sqlLastConcat($sql);
        }
    }

    /**
     * 聚合函数sql
     * @param Query $query
     * @return string
     */
    public function sqlForAggregate(Query $query)
    {
        $sql = $this->dealSqlOrders($query);
        unset($sql['fields']);
        if (!empty($sql)) {
            return $this->sqlLastConcat($sql);
        }
    }

    /**
     * 更新sql
     * @param Query $query
     * @return string
     */
    public function sqlForUpdate(Query $query)
    {
        $sql = $this->dealSqlUpdate($query);
        return $this->sqlLastConcat($sql);
    }

    /**
     * 增加sql
     * @param Query $query
     * @return string
     */
    public function sqlForInsert(Query $query)
    {
        $sql = $this->dealSqlInsert($query);
        return $this->sqlLastConcat($sql);
    }

    /**
     * 删除sql
     * @param Query $query
     * @return string
     */
    public function sqlForDelete(Query $query)
    {
        $sql = $this->dealSqlDelete($query);
        return $this->sqlLastConcat($sql);
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
     * 获取delete的sql语句
     * @param $query
     * @return mixed
     */
    public function dealSqlDelete($query)
    {
        $sql['table'] = 'delete ' . $this->tableResolve($query->table);
        $sql['wheres'] = $this->wheresResolve($query->wheres);
        return $sql;
    }

    /**
     * 获取insert的sql语句
     * @param $query
     * @return mixed
     */
    public function dealSqlInsert($query)
    {
        $insertResolve = $this->insertResolve($query->insertKeys);
        $sql['table'] = 'insert into ' . $this->escapeValue($query->table);
        $sql['keys'] = $insertResolve['keys'];
        $sql['values'] = $insertResolve['values'];
        return $sql;
    }

    /**
     * 获取update的sql语句
     * @param $query
     * @return mixed
     */
    public function dealSqlUpdate($query)
    {
        $sql['table'] = 'update ' . $this->escapeValue($query->table) . ' set';
        $sql['set'] = $this->updateResolve($query->updateKeys);
        $sql['wheres'] = $this->wheresResolve($query->wheres);
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
        return strstr($value, ' as ') ? true : false;
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
     * @return string
     */
    public function aggregateResolve($item)
    {
        return 'select ' . $item['name'] . '(' . $this->escapeValue($item['argument']) . ')';
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
        return $value[0] . '.' . $this->escapeValueNoAs($value[1]);
    }

    /**
     * 解析where条件
     * @param $item
     * @return string
     */
    public function wheresResolve($item)
    {
        $wheres = [];
        foreach ($item as $value) {
            if (is_array($value['value'])) {
                $resolveValue = $this->resolveValue($value['value']);
                $childWheres = [];
                foreach ($resolveValue as $v) {
                    $childWheres[] = "{$v['where']} {$this->escapeValue($value['field'])}{$v['exp']} ? ";
                }
                $wheres[] = $childWheres;
                continue;
            }
            $wheres[] = "{$value['where']} {$this->escapeValue($value['field'])}{$value['exp']} ? ";
        }
        return 'where ' . $this->devLeftExp($this->resolveArrayJoint($wheres));
    }

    /**
     * 如果解析出来是数组，调用该方法进行拼接
     * @param array $array
     * @param bool $hasBracket 是否一个字段条件
     * @return string
     */
    public function resolveArrayJoint(array $array, $hasBracket = false)
    {
        $item = '';
        foreach ($array as $value) {
            if (is_array($value)) {
                $item .= static::resolveArrayJoint($value, true);
            } else {
                $item .= ' ' . $value;
            }
        }
        if ($hasBracket) {
            $item = 'and (' . $this->devLeftExp($item) . ')';
        }
        return $item;
    }

    /**
     * 去除左边的连接符，and或者or
     * @param string $string
     * @return string|string[]|null
     */
    public function devLeftExp($string)
    {
        return preg_replace('/ and | or /', '', $string, 1);
    }

    /**
     * 解析groups
     * @param $item
     * @return string
     */
    public function groupsResolve($item)
    {
        $groups = '';
        foreach ($item as $value) {
            $groups .= $this->escapeValue($value) . ',';
        }
        return 'group by ' . trim($groups, ',');
    }

    /**
     * 解析having条件
     * @param $item
     * @return string
     */
    public function havingsResolve($item)
    {
        $havings = [];
        foreach ($item as $value) {
            $havings[] = "{$value['where']} {$this->escapeValue($value['field'])}{$value['exp']} ? ";
        }
        if (!empty($havings = $this->devLeftExp($this->resolveArrayJoint((array)$havings)))) {
            return 'having ' . $havings;
        }
        return '';
    }

    /**
     * 解析排序
     * @param $item
     * @return string
     */
    public function ordersResolve($item)
    {
        $orders = '';
        foreach ($item as $order) {
            $orders .= $this->escapeValue($order['field']) . ' ' . $order['direction'] . ' , ';
        }
        return trim($orders, ', ');
    }

    /**
     * 解析limit
     * @param $item
     * @return mixed
     */
    public function limitResolve($item)
    {
        return 'limit ' . $item;
    }

    /**
     * 解析offset
     * @param $item
     * @return mixed
     */
    public function offsetResolve($item)
    {
        return 'offset ' . $item;
    }

    /**
     * 解析插入的keys
     * @param $keys
     * @return array
     */
    public function insertResolve($keys)
    {
        $values = '';
        foreach ($keys as &$key) {
            $key = $this->escapeValue($key);
            $values .= '?,';
        }
        $keys = '(' . implode(',', $keys) . ')';
        $values = 'values (' . trim($values, ',') . ')';
        return ['keys' => $keys, 'values' => $values];
    }

    /**
     * 解析更新的字段
     * @param $keys
     * @return string
     */
    public function updateResolve($keys)
    {
        $set = '';
        foreach ($keys as $key){
            $set .= $this->escapeValue($key).'= ? ,';
        }
        return trim($set,',');
    }

    /**
     * 解析value
     * @param array $array
     * @return array
     */
    public function resolveValue(array $array)
    {
        $result = [];
        foreach ($array as $value) {
            $result[] = [
                'exp' => $value[0],
                'value' => $value[1],
                'where' => !empty($value[2]) ? $value[2] : 'and',
            ];
        }
        return $result;
    }

    /**
     * sql语句最后的拼接
     * @param $sql
     * @return string
     */
    public function sqlLastConcat($sql)
    {
        return !empty($sql) ? implode(' ', $sql) : '';
    }


}