<?php

namespace Panda\database\query;

use Panda\database\builder\Builder;
use Panda\database\connector\Connect;
use Panda\database\execute\Execute;

class Query
{
    /**
     * pdo连接
     * @var
     */
    protected $pdo;
    /**
     * 某一类型的连接器对象，比如MysqlConnect
     * @var
     */
    protected $connector;
    /**
     * 执行sql的类
     * @var Builder
     */
    protected $execute;
    /**
     * 构建sql的类
     * @var Builder
     */
    protected $builder;

    /**
     * 用于操作的表名
     * @var
     */
    protected $table;
    /**
     * 聚合函数
     * @var
     */
    protected $aggregate;
    /**
     *  字段
     * @var
     */
    protected $fields;
    /**
     * 关联查询
     * @var
     */
    protected $joins;
    /**
     *  条件
     * @var
     */
    protected $wheres = [];
    /**
     * 分组
     * @var array
     */
    protected $groups;
    /**
     * having条件
     * @var
     */
    protected $havings = [];
    /**
     * 排序
     * @var
     */
    protected $orders;
    /**
     *  限制数量
     * @var
     */
    protected $limit;
    /**
     *  偏移
     * @var
     */
    protected $offset;


    /**
     * 构造函数
     * Query constructor.
     */
    public function __construct($connector)
    {
        $this->connector = $connector;
        $this->builder = new Builder();
        $this->execute = new Execute();
    }

    /**
     * 表名
     * @return $this
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * 聚合函数操作,直接执行相应的查询了
     * @param $operate
     * @param null $parameter
     * @return $this
     */
    public function aggregate($operate, $parameter = null)
    {
        $this->aggregate['operate'] = $operate;
        $this->aggregate['parameter'] = $parameter;
        return $this;
    }

    /**
     * 统计
     * @param null $parameter
     * @return $this
     */
    public function count($parameter = null)
    {
        return $this->aggregate('count', $parameter);
    }

    /**
     * 最大
     * @param null $parameter
     * @return $this
     */
    public function max($parameter = null)
    {
        return $this->aggregate('max', $parameter);
    }

    /**
     * 最小
     * @param null $parameter
     * @return $this
     */
    public function min($parameter = null)
    {
        return $this->aggregate('min', $parameter);
    }

    /**
     * 平均
     * @param null $parameter
     * @return $this
     */
    public function avg($parameter = null)
    {
        return $this->aggregate('avg', $parameter);
    }

    /**
     * 求和
     * @param null $parameter
     * @return $this
     */
    public function sum($parameter = null)
    {
        return $this->aggregate('sum', $parameter);
    }

    /**
     * 字段
     * @param array $parameter
     * @return $this
     */
    public function field($parameter = [])
    {
        if (is_array($parameter)) {
            $parameter = join(',', $parameter);
        }
        $this->fields = $parameter;
        return $this;
    }

    /**
     * 添加条件
     * @param $field
     * @param $exp
     * @param $value
     * @return $this
     */
    public function where($field, $exp = '', $value = '')
    {
        if (is_array($field)) { //直接就规定只能是二维数组
            $this->wheres = array_merge($this->wheres, $field);
        } else {
            $this->wheres[] = [$field, $exp, $value];
        }
        return $this;
    }

    /**
     * 排序
     * @param $parameter
     * @return $this
     */
    public function orders($parameter)
    {
        if (is_array($parameter)) {
            $this->orders = join(',', $parameter);
        } else {
            $this->orders = $parameter;
        }
        return $this;
    }

    /**
     * 偏移
     * @param int $parameter
     * @return $this
     */
    public function offset($parameter = 0)
    {
        $this->offset = $parameter;
        return $this;
    }

    /**
     * 限制数量
     * @param $parameter
     * @return $this
     */
    public function limit($parameter)
    {
        $this->limit = $parameter;
        return $this;
    }

    /**
     * 关联查询
     * @param $table (关联的表)
     * @param $onFirst
     * @param $onSecond
     * @param string $type (关联类型)
     * @return $this
     */
    public function joins($table, $onFirst, $onSecond, $type = '')
    {
        $this->joins[] = [
            'table' => $table,
            'onFirst' => $onFirst,
            'onSecond' => $onSecond,
            'type' => $type
        ];
        return $this;
    }

    /**
     * 分组group by
     * @param $parameter
     * @return $this
     */
    public function group($parameter)
    {
        if (is_array($parameter)) {
            $this->groups = join(',', $parameter);
        } else {
            $this->groups = $parameter;
        }
        return $this;
    }

    /**
     * having条件
     */
    public function having($field, $exp = '', $value = '')
    {
        if (is_array($field)) { //直接就规定只能是二维数组
            $this->havings = array_merge($this->wheres, $field);
        } else {
            $this->havings[] = [$field, $exp, $value];
        }
        return $this;
    }
    /**
     * 要想执行sql语句，需要以下几个步骤:
     *      1.获取PDO实例化连接(connector作用)
     *      2.获取最终要执行的sql语句(builder作用)
     *      3.执行sql语句
     *      4.对结果集进行处理
     *      5.返回最终的处理结果
     * 问题一:如何处理sql断开连接的问题,也就是pdo实例为null的问题,可以这样处理如果执行错误，那么就用try catch捕捉这个pdo问题，然后再去执行这个查询获取其他操作
     */

    /**
     * 查询全部
     */
    public function select($method = null)
    {
        //获取实例化的连接
        if (empty($this->pdo)) {
            $this->pdo = $this->connector->getConnect();
        }
//        var_dump($this->pdo);
        //获取最终要执行的语句
        if (is_null($method)) {
            $method = __FUNCTION__;
        }
        $arguments = $this->resolveParams();
        $sql = $this->builder->getSql($arguments, $method);
        try {
            $PDOStatement = $this->pdo->query($sql);
            $result = $PDOStatement->fetchAll();
            return $result;
        } catch (\PDOException $e) {
            //这个地方需要限制次数,如果不加限制，就死循环了
//            $this->flush()->$method();
            var_dump($sql);
            echo $e->getLine() . '/' . $e->getMessage();
            die();
        }
    }

    /**
     * 获取所有的变量
     */
    public function resolveParams()
    {
        return [
            'fields' => $this->fields,
            'table' => $this->table,
            'joins' => $this->joins,
            'wheres' => $this->wheres,
            'groups' => $this->groups,
            'havings' => $this->havings,
            'orders' => $this->orders,
            'limit' => $this->limit,
            'offset' => $this->offset
        ];
    }

    /**
     * 清除pdo
     */
    public function flush()
    {
        $this->pdo = null;
        return $this;
    }

    /**
     * 查询一条语句（limit 1）
     */
    public function first()
    {
        return $this->select('first');
    }

    /**
     * 更新
     */
    public function update($data)
    {
        //获取实例化的连接
        if (empty($this->pdo)) {
            $this->pdo = $this->connector->getConnect();
        }
//        var_dump($this->pdo);die();
        //获取最终要执行的语句
        $method = __FUNCTION__;
        $arguments = $this->resolveParams();
        $sql = $this->builder->getSql($arguments, $method, $data);
        try {
            $result = $this->pdo->exec($sql);
            return $result;
        } catch (\PDOException $e) {
            var_dump($sql);
            echo $e->getLine() . '/' . $e->getMessage();
            die();
        }
    }

    /**
     * 删除
     */
    public function delete()
    {
        //获取实例化的连接
        if (empty($this->pdo)) {
            $this->pdo = $this->connector->getConnect();
        }
//        var_dump($this->pdo);die();
        //获取最终要执行的语句
        $method = __FUNCTION__;
        $arguments = $this->resolveParams();
        $sql = $this->builder->getSql($arguments, $method);
        try {
            $result = $this->pdo->exec($sql);
            return $result;
        } catch (\PDOException $e) {
            var_dump($sql);
            echo $e->getLine() . '/' . $e->getMessage();
            die();
        }
    }

    /**
     * 添加
     */
    public function insert($data)
    {
        //获取实例化的连接
        if (empty($this->pdo)) {
            $this->pdo = $this->connector->getConnect();
        }
//        var_dump($this->pdo);die();
        //获取最终要执行的语句
        $method = __FUNCTION__;
        $arguments = $this->resolveParams();
        $sql = $this->builder->getSql($arguments, $method, $data);
//        var_dump($sql);die();
        try {
            $result = $this->pdo->exec($sql);
            return $result;
        } catch (\PDOException $e) {
            var_dump($sql);
            echo $e->getLine() . '/' . $e->getMessage();
            die();
        }
    }

}