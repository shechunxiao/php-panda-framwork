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
     * @param $query
     * @param $type
     */
    public function getSql($arguments, $type)
    {
        $this->arguments = $arguments;
        $arguments = $this->resolveParameters($arguments);
        switch (strtolower($type)) {
            case 'select':
                $sql = $this->select($arguments);
                break;
            case 'first':
                $sql = $this->first($arguments);
                break;
            case 'update':
                $sql = $this->update($arguments);
                break;
            case 'delete':
                $sql = $this->delete($arguments);
                break;
            case 'insert':
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
        foreach ($arguments as $key=>$value){
            if (!is_null($value)){
                $sql .= $value;
            }
        }
        return $sql;
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
            'from' => ' from '.$arguments['table'].' ',
            'joins' => $this->resolveJoins($arguments['joins']),
            'wheres' => $this->resolveWheres($arguments['wheres']),
            'groups' => !empty($arguments['groups'])?' group by '.$arguments['groups'].' ':'',
            'havings' => $this->resolveHavings($arguments['havings']),
            'orders' => !empty($arguments['orders'])?' order by '.$arguments['orders'].' ':'',
            'limit' => !empty($arguments['limit'])?' limit '.$arguments['limit'].' ':'',
            'offset' => !empty($arguments['offset'])?' offset '.$arguments['offset']:''
        ];
        return $param;
    }


    /**
     * 解析join连接
     */
    public function resolveJoins($joins)
    {
        $join = '';
        if (empty($joins)){
            return $join;
        }
        foreach ($joins as $key=>$value){
            if ($value['type']){
                $join .= strtolower($value['type']).' join '.$value['table'].' on '.$value['onFirst'].' = '.$value['onSecond'].' ';
            }else{
                $join .= 'join '.$value['table'].' on '.$value['onFirst'].' = '.$value['onSecond'].' ';
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
        if (empty($wheres)){
            return $where;
        }
        foreach ($wheres as $key=>$value){
            if (strtolower($value[1]) == 'between'){
                $where .= ' and '.$value[0].' between '.$value[2][0].' and '.$value[2][1];
            }elseif (strtolower($value[1]) == 'in'){
                $inArr = join(',',$value[2]);
                $where .= ' and '.$value[0].' in '."($inArr)";
            }else{
                $where .= ' and '.$value[0].' '.$value[1].' '.$value[2];
            }
        }
        $where = substr($where,4);
        $where = 'where'.$where;
        return $where;
    }

    /**
     * 解析havings
     */
    public function resolveHavings($wheres)
    {
        $where = '';
        if (empty($wheres)){
            return $where;
        }
        foreach ($wheres as $key=>$value){
            if (strtolower($value[1]) == 'between'){
                $where .= ' and '.$value[0].' between '.$value[2][0].' and '.$value[2][1];
            }elseif (strtolower($value[1]) == 'in'){
                $inArr = join(',',$value[2]);
                $where .= ' and '.$value[0].' in '."($inArr)";
            }else{
                $where .= ' and '.$value[0].' '.$value[1].' '.$value[2];
            }
        }
        $where = substr($where,4);
        $where = 'having'.$where;
        return $where;
    }

}