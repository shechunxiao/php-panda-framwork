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
     */
    public function getSql($query){
        $this->query = $query;
        



    }

}