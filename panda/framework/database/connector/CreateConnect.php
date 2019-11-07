<?php

namespace Panda\database\connector;

use Panda\database\query\Query;

class CreateConnect
{
    /**
     * 创建连接对象,比如如果是mysql就实例化mysql驱动对象。
     */
    public function connect($type)
    {
        //第一步实例化connect类
        switch ($type) {
            case 'mysql':
                $connector =  new MysqlConnect();
                break;
            case 'pgsql':
                $connector = 'pgsql';
                break;
            case 'sqlite':
                $connector = 'sqlite';
                break;
            case 'sqlsrv':
                $connector= 'sqlsrv';
                break;
        }
        //第二步实例化query类
        return new Query($connector);
    }


}