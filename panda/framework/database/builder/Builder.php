<?php

namespace Panda\database\builder;

class Builder
{
    /**
     * 参数数组
     * @var
     */
    protected $arguments;
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
     */
    public function getSql($arguments, $type, $data = [])
    {
        $this->arguments = $arguments;
        switch (strtolower($type)) {
            case 'select':
                $arguments = $this->resolveParameters($arguments);
                $sql = $this->select($arguments);
                break;
            case 'first':
                $arguments = $this->resolveParameters($arguments);
                $sql = $this->first($arguments);
                break;
            case 'update':
                $arguments = $this->resolveParametersUpdate($arguments, $data);
                $sql = $this->update($arguments);
                break;
            case 'delete':
                $arguments = $this->resolveParameters($arguments);
                $sql = $this->delete($arguments);
                break;
            case 'insert':
                $arguments = $this->resolveParametersInsert($arguments,$data);
                $sql = $this->insert($arguments);
                break;
        }
        return $sql;
    }

    /**
     * 获取select的对应语句
     */
    public function select($arguments)
    {
        $sql = ' select ';
        foreach ($arguments as $key => $value) {
            if (!is_null($value)) {
                $sql .= $value;
            }
        }
        return $sql;
    }

    /**
     * 获取first的对应语句
     */
    public function first($arguments)
    {
        $arguments['limit'] = ' limit 1 ';
        return $this->select($arguments);
    }

    /**
     * 获取update的对应语句
     */
    public function update($arguments)
    {
        $sql = 'update ' . $arguments['table'] . ' set '.$arguments['params'] . ' '.$arguments['wheres'];
        return $sql;
    }

    /**
     * 获取delete的对应语句
     */
    public function delete($arguments)
    {
        $sql = 'delete '.$arguments['from'].$arguments['wheres'];
        return $sql;
    }

    /**
     * 获取insert的对应语句
     */
    public function insert($arguments)
    {
        $sql = 'insert into '.$arguments['table'].$arguments['params'];
        return $sql;
    }

    /**
     * 聚合函数单独调用
     */
    public function aggregate()
    {

    }

    /**
     * 解析所有的变量
     */
    public function resolveParameters($arguments)
    {
        $param = [
            'fields' => $arguments['fields'],
            'from' => ' from ' . $arguments['table'] . ' ',
            'joins' => $this->resolveJoins($arguments['joins']),
            'wheres' => $this->resolveWheres($arguments['wheres']),
            'groups' => !empty($arguments['groups']) ? ' group by ' . $arguments['groups'] . ' ' : '',
            'havings' => $this->resolveHavings($arguments['havings']),
            'orders' => !empty($arguments['orders']) ? ' order by ' . $arguments['orders'] . ' ' : '',
            'limit' => !empty($arguments['limit']) ? ' limit ' . $arguments['limit'] . ' ' : '',
            'offset' => !empty($arguments['offset']) ? ' offset ' . $arguments['offset'] : ''
        ];
        return $param;
    }

    /**
     * 解析update参数
     */
    public function resolveParametersUpdate($arguments, $data)
    {
        $param = [
            'table' => $arguments['table'],
            'params' => $this->resolveUpdate($data),
            'wheres' => $this->resolveWheres($arguments['wheres']),
        ];
        return $param;
    }

    /**
     * 解析insert参数
     */
    public function resolveParametersInsert($arguments,$data){
        $param = [
            'table'=>$arguments['table']  ,
            'params'=>$this->resolveInsert($data)
        ];
        return $param;
    }

    /**
     * 解析相关参数
     * @param $data
     * @return string
     */
    public function resolveInsert($data){
        if (empty($data)){
            return '';
        }
        foreach ($data as &$value){
            if (is_string($value)){
                $value = "'$value'";
            }
        }
        $fields = join(',',array_keys($data));
        $values = join(',',array_values($data));
        $insert = " ($fields) values ($values) ";
        return $insert;
    }
    /**
     * 解析update参数
     */
    public function resolveUpdate($data)
    {
        if (empty($data)) {
            return '';
        }
        $update = '';
        foreach ($data as $key=>$value){
            $update .= " $key='".$value."',";
        }
        $update = trim($update,',');
        return $update;
    }



    /**
     * 解析join连接
     */
    public function resolveJoins($joins)
    {
        $join = '';
        if (empty($joins)) {
            return $join;
        }
        foreach ($joins as $key => $value) {
            if ($value['type']) {
                $join .= strtolower($value['type']) . ' join ' . $value['table'] . ' on ' . $value['onFirst'] . ' = ' . $value['onSecond'] . ' ';
            } else {
                $join .= 'join ' . $value['table'] . ' on ' . $value['onFirst'] . ' = ' . $value['onSecond'] . ' ';
            }
        }
        return $join;
    }

    /**
     * 解析wheres
     */
    public function resolveWheres($wheres)
    {
        $where = '';
        if (empty($wheres)) {
            return $where;
        }
        foreach ($wheres as $key => $value) {
            if (strtolower($value[1]) == 'between') {
                $where .= ' and ' . $value[0] . ' between ' . $value[2][0] . ' and ' . $value[2][1];
            } elseif (strtolower($value[1]) == 'in') {
                $inArr = join(',', $value[2]);
                $where .= ' and ' . $value[0] . ' in ' . "($inArr)";
            } else {
                $where .= ' and ' . $value[0] . ' ' . $value[1] . ' ' . $value[2];
            }
        }
        $where = substr($where, 4);
        $where = 'where' . $where;
        return $where;
    }

    /**
     * 解析havings
     */
    public function resolveHavings($wheres)
    {
        $where = '';
        if (empty($wheres)) {
            return $where;
        }
        foreach ($wheres as $key => $value) {
            if (strtolower($value[1]) == 'between') {
                $where .= ' and ' . $value[0] . ' between ' . $value[2][0] . ' and ' . $value[2][1];
            } elseif (strtolower($value[1]) == 'in') {
                $inArr = join(',', $value[2]);
                $where .= ' and ' . $value[0] . ' in ' . "($inArr)";
            } else {
                $where .= ' and ' . $value[0] . ' ' . $value[1] . ' ' . $value[2];
            }
        }
        $where = substr($where, 4);
        $where = 'having' . $where;
        return $where;
    }

}