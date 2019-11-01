<?php

namespace Panda\facade;
class Db extends Facade
{
    /**
     * 用于传递是哪个类的门面
     * @return string
     */
    public static function getFacadeClass(){
        return 'Db';
    }
}