<?php

namespace Panda\database\result;
class Result
{
    /**
     * 处理获取某一个字段的公用函数
     * @param array $array
     * @param null $fields
     * @return array
     */
    public function columns(array $array, $fields = null)
    {
        if ($fields == null){
            return $array;
        }
        return array_column($array,$fields);
    }

}