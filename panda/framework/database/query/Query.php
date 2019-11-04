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
     * 构造函数
     * Query constructor.
     */
    public function __construct($connector)
    {
        $this->connector = $connector;
    }
    public function table(){
        return $this;
    }
    public function field(){
        return $this;
    }
    public function select(){
        var_dump('select');
        return $this;
    }


}