<?php

namespace Panda\database\query;

use Panda\container\Container;
use Panda\database\builder\Builder;
use Panda\database\execute\Execute;

class Query
{
    /**
     * pdo连接,改成了静态变量，因为如果不是静态的事务回滚和提交追踪不到相应的pdo连接
     * @var
     */
    public static $pdo;
    /**
     * 容器，为了获取database的配置参数
     * @var Container
     */
    public $container;
    /**
     * 某一类型的连接器对象，比如MysqlConnect
     * @var
     */
    public $connector;
    /**
     * 构建sql的类
     * @var Builder
     */
    public $builder;
    /**
     * 执行sql语句
     * @var Execute
     */
    public $execute;

    /**
     * 需要插入的keys数据
     * @var
     */
    public $insertKeys = [];
    /**
     * 需要更新的keys数据
     * @var array
     */
    public $updateKeys = [];

    /**
     * 事务开启的数量,目的是为了直接commit或者rollback而没开启事务造成一些异常
     * @var int
     */
    private static $transactions = 0;
    /**
     * 需要处理的绑定,便于生成sql的时候直接调用,最主要是为了实现参数绑定(里面的顺序不可更改)
     * @var array
     */
    public $binds = [
        'update' => [],
        'wheres' => [],
        'havings' => [],
        'insert' => [],
    ];
    /**
     * 用于操作的表名
     * @var
     */
    public $table;
    /**
     * 聚合函数
     * @var
     */
    public $aggregate;
    /**
     *  字段
     * @var
     */
    public $fields = ['*'];
    /**
     * 关联查询
     * @var
     */
    public $joins;
    /**
     *  条件
     * @var
     */
    public $wheres = [];
    /**
     * 分组
     * @var array
     */
    public $groups;
    /**
     * having条件
     * @var
     */
    public $havings = [];
    /**
     * 排序
     * @var
     */
    public $orders;
    /**
     *限制数量
     * @var
     */
    public $limit;
    /**
     *偏移
     * @var
     */
    public $offset;
    /**
     * @var int
     */

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
        $this->execute = new Execute();
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
     * @param string $where
     * @return $this
     */
    public function where($field, $exp = null, $value = null, $where = 'and')
    {
        if (is_array($field)) {
            $this->dealWhereArray($field);
        } else {
            $this->wheres[] = [
                'field' => $field,
                'exp' => $exp,
                'value' => $value,
                'where' => $where
            ];
            //绑定where的值到相应的binds中去，目的是实现pdo的参数绑定
            $this->addBinds($value, 'wheres');
        }
        return $this;
    }

    /**
     * $where['id'] = [
     *      ['>',1,'and'],
     *      ['<',10,'or']
     * ]
     * $where['id'] = ['>','1']
     * $where[] = ['id','>',1]
     * 三种情况，
     *      第一种，key为字符串，且为二维数组
     *      第二种，key为字符串，且为一维数组
     *      第三种，key为数字，且为一维数组
     */
    /**
     * where条件为数组
     * @param $fields
     */
    public function dealWhereArray($fields)
    {
        foreach ($fields as $key => $value) {
            $isOneLevel = true;
            if (count($value) != count($value, COUNT_RECURSIVE)) {
                $isOneLevel = false;
            }
            if (is_numeric($key) && $isOneLevel) { //key为数字，且为一维数组
                $this->where(...array_values($value));
            } elseif (!is_numeric($key) && $isOneLevel) {//key为字符串，且为一维数组
                $this->where($key, ...array_values($value));
            } elseif (!is_numeric($key) && !$isOneLevel) {//key为字符串，且为二维数组
                $this->where($key, '=', $value);
            }
        }
    }

    /**
     * 添加绑定，都是值
     * @param $value
     * @param $type
     */
    public function addBinds($value, $type)
    {
        $isOneLevel = true;
        if (count($value) != count($value, COUNT_RECURSIVE)) {
            $isOneLevel = false;
        }
        if (is_array($value) && $isOneLevel) {
            $this->binds[$type] = array_values(array_merge($this->binds[$type], $value));
        } elseif (is_array($value) && !$isOneLevel) {
            $bindArr = [];
            foreach ($value as $K => $v) {
                if (!isset($v[1])) {
                    echo '缺少表达式符号，如>,=,<';
                    die();
                }
                array_push($bindArr, $v[1]);
            }
            $this->binds[$type][] = $bindArr;
        } else {
            $this->binds[$type][] = $value;
        }
    }

    /**
     * whereOr查询
     * @param $field
     * @param null $exp
     * @param null $value
     * @return $this
     */
    public function whereOr($field, $exp = null, $value = null)
    {
        return $this->where($field, $exp, $value, 'or');
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
     * @param string $where
     * @return Query
     */
    public function having($field, $exp = null, $value = null, $where = 'and')
    {
        $this->havings[] = [
            'field' => $field,
            'exp' => $exp,
            'value' => $value,
            'where' => $where
        ];
        $this->addBinds($value, 'havings');
        return $this;
    }

    /**
     * 最大值
     * @param $argument
     * @return void
     */
    public function max($argument = null)
    {
        return $this->aggregate('max', $argument);
    }

    /**
     * 最小值
     * @param $argument
     * @return int
     */
    public function min($argument = null)
    {
        return $this->aggregate('min', $argument);
    }

    /**
     * 平均值
     * @param $argument
     * @return void
     */
    public function avg($argument = null)
    {
        return $this->aggregate('avg', $argument);
    }

    /**
     * 总和
     * @param $argument
     * @return void
     */
    public function sum($argument = null)
    {
        return $this->aggregate('sum', $argument);
    }

    /**
     * 统计
     * @param $argument
     * @return void
     */
    public function count($argument = '*')
    {
        return $this->aggregate('count', $argument);
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
        if (empty(static::$pdo)) {
            return static::$pdo = $this->connector->getConnect($this->getConfig());
        }
        return static::$pdo;
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
     * 清除pdo
     */
    public function flush()
    {
        static::$pdo = null;
        return $this;
    }

    /**
     * 增加
     * @param $data
     */
    public function insert($data)
    {
        $this->insertKeys = array_keys($data);
        $this->addBinds(array_values($data), 'insert');
        $sql = $this->builder->sqlForInsert($this);
        return $this->execute->insert($this, $sql);
    }

    /**
     * 添加数据并返回id值
     * @param $data
     * @return mixed
     */
    public function insertGetId($data)
    {
        $this->insert($data);
        $id = $this->getPdo()->lastInsertId();
        return is_numeric($id) ? (int)$id : $id;
    }

    /**
     * 删除
     */
    public function delete()
    {
        $sql = $this->builder->sqlForDelete($this);
        return $this->execute->delete($this, $sql);
    }

    /**
     * 更新
     * @param $data
     */
    public function update($data)
    {
        $this->updateKeys = array_keys($data);
        $this->addBinds(array_values($data), 'update');
        $sql = $this->builder->sqlForUpdate($this);
        return $this->execute->update($this, $sql);
    }

    /**
     * 查询多条语句
     */
    public function select()
    {
        $sql = $this->builder->sqlForSelect($this);
        return $this->execute->select($this, $sql);
    }

    /**
     * 查询单条语句
     */
    public function first()
    {
        $this->limit(1);
        $sql = $this->builder->sqlForSelect($this);
        return $this->execute->first($this, $sql);
    }

    /**
     * 获取某一列
     * @param array $parameters
     * @return array
     */
    public function columns($parameters)
    {
        $sql = $this->builder->sqlForSelect($this);
        return $this->execute->columns($this, $sql, $parameters);
    }

    /**
     * 获取某一条记录的某一个值
     * @param null $field
     * @return
     */
    public function value($field = null)
    {
        return $this->first()[$field];
    }

    /**
     * 聚合函数统一处理函数
     * @param $name
     * @param $argument
     * @return void
     */
    public function aggregate($name, $argument)
    {
        $this->aggregate = ['name' => $name, 'argument' => $argument];
        $sql = $this->builder->sqlForAggregate($this);
        return $this->execute->runAggregate($this, $sql);
    }

    /**以下为事务的处理，mysql是支持事务保存点的，我们初始版本不做支持*/

    /**
     * 开启事务
     */
    public function beginTransaction()
    {
        if (static::$transactions == 0) {
            try {
                $this->getPdo()->beginTransaction();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        static::$transactions += 1;
    }

    /**
     * 回滚事务
     */
    public function rollBack()
    {
        //如果事务数量为0，那么不需要回滚
        if (static::$transactions >= 1) {
            $this->getPdo()->rollBack();
        }
    }

    /**
     * 提交
     */
    public function commit()
    {
        if (static::$transactions == 1) {
            $this->getPdo()->commit();
        }
        //这个的目的是为了如果没有执行beginTransaction开启事务就commit了，造成事务数为-1,默认连接数应该是0
        static::$transactions = max(0, static::$transactions - 1);
    }


}