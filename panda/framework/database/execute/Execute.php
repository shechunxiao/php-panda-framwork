<?php

namespace Panda\database\execute;

class Execute
{
    /**
     * 结果集查询
     * @var
     */
    protected $result;

    /**
     * select查询
     * @param Query $query
     * @param $sql
     * @return
     */
    public function runSelect($query,$sql)
    {
        if (is_null($pdo = $query->pdo)){
            $pdo = $query->getPdo();
        }
        //预处理
        $statement = $pdo->prepare($sql);
        if ($binds = $this->getBinds($query->binds)){
            foreach($binds as $key=>$value){
                $statement->bindValue($key+1,$value);
            }
        }
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    /**
     * 获取绑定的参数
     * @param $binds
     * @return array
     */
    public function getBinds($binds){
        $bindValue = [];
        $binds = array_values($binds);
        foreach ($binds as $item){
            if (is_array($item)){
                foreach ($item as $key=>$value){
                    $bindValue[] = $value;
                }
            }
        }
        return $bindValue;
    }

    /**
     * 获取聚合函数的值
     * @param $query
     * @param $sql
     * @return
     */
    public function runAggregate($query,$sql){
        $result = $this->runSelect($query,$sql);
        if ($result){
            return array_values($result[0])[0];
        }
    }

    /**
     * 获取某一行的结果
     */
    public function first()
    {

    }

    /**
     * 获取某一列
     */
    public function columns()
    {

    }

    /**
     * 请求数据
     */
    public function queryData()
    {

    }

    /**
     * 更新数据
     */
    public function update()
    {

    }

    /**
     * 添加数据
     */
    public function insert(){

    }

    /**
     * 删除数据
     */
    public function delete()
    {

    }



}