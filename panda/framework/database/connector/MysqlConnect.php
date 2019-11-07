<?php

namespace Panda\database\connector;

class MysqlConnect extends Connect
{
    /**
     * 获取连接
     * @param array $config
     * @return \PDO
     */
    public function getConnect(array $config)
    {
        $dns = $this->getDns($config);
        $connect = $this->connect($dns, $config['username'], $config['password']);
        $this->configCharset($connect, $config);
        return $connect;
    }

    /**
     * 构建dns连接参数
     * @param $config
     * @return string
     */
    public function getDns($config)
    {
        extract($config, EXTR_SKIP);
        return isset($port)
            ? "mysql:host={$host};port={$port};dbname={$dbname};"
            : "mysql:host={$host};dbname={$dbname};";
    }

    /**
     * 设置字符编码格式
     * @param $connect
     * @param $config
     * @return mixed
     */
    protected function configCharset($connect, $config)
    {
        if (!isset($config['charset'])) {
            return $connect;
        }
        $connect->prepare("set names '{$config['charset']}'" . $this->getCollation($config))->execute();
    }

    /**
     * 获取结果集
     * @param array $config
     * @return string
     */
    protected function getCollation(array $config)
    {
        return (isset($config['collation']) && !is_null($config['collation'])) ? " collate '{$config['collation']}'" : '';
    }


}