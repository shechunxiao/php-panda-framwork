<?php

namespace Panda\foundation;
use Panda\container\Container;
use Panda\database\connector\CreateConnect;

class Db
{
    /**
     * 构建思路:
     *      1.Db通过门面进入到该Db类，触发构造函数，实例化，然后进入到__call方法，执行相应的方法。
     *      2.
     */
    /**
     * 服务容器
     * @var Container
     */
    protected $container;
    /**
     * 连接对象,可能有mysql等数据库类型,这里是暂时只应用了mysql,比如mysql的话，对应的连接对象就是panda/framework/database/connector/MysqlConnect.php该PHP文件的实例化对象。
     * @var CreateConnect
     */
    protected $createConnect;
    /**
     * 构造初始化
     * Db constructor.
     */
    public function __construct(Container $container,CreateConnect $createConnect)
    {
        $this->container = $container;
        $this->createConnect = $createConnect;
    }

    /**
     * 连接,这个连接可以调用相应的方法，比如field,select,first,where,value,whereOr等等,通过__call的启示，可以通过__call再次进行分发到query查询类中。
     * 所有的核心资源是query，通过query的get等方法，触发解析等操作
     * 这个方法需要判断是否connect其他的数据库配置,从而实现数据库的切换
     */
    public function connection(){
        return $this->createConnect->connect();
    }
    /**是
     * 通过这个魔术方法分发其他方法
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($method, $arguments)
    {
        $connection = $this->connection();
//        $connection->$method(...$arguments);
//        return $this;
    }

}