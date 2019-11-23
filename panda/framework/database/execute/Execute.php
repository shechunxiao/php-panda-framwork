<?php

namespace Panda\database\execute;

use Panda\database\result\Result;

class Execute
{
    /**
     * 结果集查询
     * @var
     */
    protected $result;
    /**
     * 结果
     * @var
     */
    protected $resultContainer;

    /**
     * 构造函数
     * Execute constructor.
     */
    public function __construct()
    {
        if (empty($this->result)) {
            $this->resultContainer = new Result();
        }
    }

    /**
     * select查询
     * @param Query $query
     * @param $sql
     * @return
     */
    public function runSelect($query, $sql)
    {
        if (is_null($pdo = $query->pdo)) {
            $pdo = $query->getPdo();
        }
        //捕获相关错误
        try {
            $statement = $pdo->prepare($sql);
            if ($binds = $this->getBinds($query->binds)) {
                foreach ($binds as $key => $value) {
                    $statement->bindValue($key + 1, $value);
                }
            }
            $statement->execute();
            $this->result = $statement->fetchAll();
        } catch (\PDOException $e) {
            echo $e->getMessage() . ' Line:' . $e->getLine() . ' File' . $e->getFile();
            die();
        }
        return $this->result;
    }

    /**
     * update，delete，insert公共的pdo执行函数
     * @param $query
     * @param $sql
     */
    public function runCommon($query, $sql)
    {
        if (is_null($pdo = $query->pdo)) {
            $pdo = $query->getPdo();
        }
        //捕获相关错误
        try {
            $statement = $pdo->prepare($sql);
            if ($binds = $this->getBinds($query->binds)) {
                foreach ($binds as $key => $value) {
                    $statement->bindValue($key + 1, $value);
                }
            }
            $statement->execute();
            $this->result = $statement->rowCount();
        } catch (\PDOException $e) {
            echo $e->getMessage() . ' Line:' . $e->getLine() . ' File' . $e->getFile();
            die();
        }
        return $this->result;
    }

    /**
     * 获取绑定的参数
     * @param $binds
     * @return array
     */
    public function getBinds($binds)
    {
        $bindValue = [];
        $binds = array_values($binds);
        foreach ($binds as $item) {
            if (is_array($item)) {
                foreach ($item as $key => $value) {
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
    public function runAggregate($query, $sql)
    {
        $result = $this->runSelect($query, $sql);
        if ($result) {
            return array_values($result[0])[0];
        }
    }

    /**
     * 获取某一行的结果
     * @param $query
     * @param $sql
     * @return
     */
    public function first($query, $sql)
    {
        $this->runSelect($query, $sql);
        return $this->result[0];
    }

    /**
     * 获取某一列
     * @param $query
     * @param $sql
     * @param null $fields
     * @return array
     */
    public function columns($query, $sql, $fields = null)
    {
        $this->runSelect($query, $sql);
        return $this->resultContainer->columns($this->result, $fields);
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
    public function insert()
    {

    }

    /**
     * 删除数据
     * @param $query
     * @param $sql
     */
    public function delete($query, $sql)
    {
        return $this->runCommon($query, $sql);
    }


}