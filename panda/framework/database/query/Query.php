<?php

namespace Panda\database\query;

use Panda\container\Container;
use Panda\database\builder\Builder;

class Query
{
    /**
     * pdo连接
     * @var
     */
    protected $pdo;
    /**
     * 容器，为了获取database的配置参数
     * @var Container
     */
    protected $container;
    /**
     * 某一类型的连接器对象，比如MysqlConnect
     * @var
     */
    protected $connector;
    /**
     * 构建sql的类
     * @var Builder
     */
    protected $builder;
    /**
     * 需要处理的绑定,便于生成sql的时候直接调用,最主要是为了实现参数绑定
     * @var array
     */
    protected $binds = [
        'joins' => [],
        'wheres' => [],
        'orders' => [],
        'havings' => [],
    ];
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
     *限制数量
     * @var
     */
    protected $limit;
    /**
     *偏移
     * @var
     */
    protected $offset;


    /**
     * 构造函数
     * Query constructor.
     * @param $connector
     */
    public function __construct($connector)
    {
        $this->connector = $connector;
        $this->container = new Container();
        $this->builder = new Builder();
    }

    /**
     * 表名
     * @param $table
     * @return $this
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * 字段(数组是为了后面处理加``)
     * @param array $parameter
     * @return $this
     */
    public function field($parameter = ['*'])
    {
        $this->fields = is_array($parameter) ? $parameter : func_get_args();
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
     * @param $field
     * @param string $direction
     * @return $this
     */
    public function orders($field, $direction = 'asc')
    {
        $this->orders[] = [
            'field' => $field,
            'direction' => strtolower($direction) == 'asc' ? 'asc' : 'desc'
        ];
        return $this;
    }

    /**
     * 偏移
     * @param int $parameter
     * @return $this
     */
    public function offset($parameter)
    {
        $this->offset = max(0, $parameter);
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
     * 关联查询(type有4中，inner，left，right，cross)
     * @param $table
     * @param $first
     * @param $operator
     * @param $second
     * @param string $type
     * @return Query
     */
    public function join($table, $first, $operator, $second, $type = 'inner')
    {
        $this->joins[] = [
            'table' => $table,
            'first' => $first,
            'operator' => $operator,
            'second' => $second,
            'type' => $type
        ];
        return $this;
    }

    /**
     * 分组group by
     * @param array $parameters
     * @return $this
     */
    public function group(...$parameters)
    {
        foreach ($parameters as $parameter) {
            $this->groups[] = $parameter;
        }
        return $this;
    }

    /**
     * having条件
     * @param $field
     * @param string $exp
     * @param string $value
     * @return Query
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
     * 获取pdo连接
     * @return mixed
     */
    public function getPdo()
    {
        if (empty($this->pdo)) {
            return $this->pdo = $this->connector->getConnect($this->getConfig());
        }
        return $this->pdo;
    }

    /**
     * 获取database配置文件
     * @return array
     */
    public function getConfig()
    {
        return isset($this->container->getConfig()['database']) ? $this->container->getConfig()['database'] : [];
    }

    /**
     * 获取sql语句
     * @return string
     */
    public function getSql()
    {
        return $this->builder->getSql($this);
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
     * 查询全部
     */
    public function select($method = null)
    {
        $pdo = $this->getPdo();
        //获取最终要执行的语句
        if (is_null($method)) {
            $method = __FUNCTION__;
        }
        $arguments = $this->resolveParams();
        $sql = $this->builder->getSql($arguments, $method);
        try {
            $PDOStatement = $pdo->query($sql);
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
        $pdo = $this->getPdo();
        //获取最终要执行的语句
        $method = __FUNCTION__;
        $arguments = $this->resolveParams();
        $sql = $this->builder->getSql($arguments, $method, $data);
        try {
            $result = $pdo->exec($sql);
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
        $pdo = $this->getPdo();
        //获取最终要执行的语句
        $method = __FUNCTION__;
        $arguments = $this->resolveParams();
        $sql = $this->builder->getSql($arguments, $method);
        try {
            $result = $pdo->exec($sql);
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
        $pdo = $this->getPdo();
        //获取最终要执行的语句
        $method = __FUNCTION__;
        $arguments = $this->resolveParams();
        $sql = $this->builder->getSql($arguments, $method, $data);
//        var_dump($sql);die();
        try {
            $result = $pdo->exec($sql);
            return $result;
        } catch (\PDOException $e) {
            var_dump($sql);
            echo $e->getLine() . '/' . $e->getMessage();
            die();
        }
    }

}