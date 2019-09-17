<?php
require 'bootstrap/autoload.php';

/**
 * 总结知识点
 * PDO
 *      PDO::exec() 在一个单独的函数调用中执行一条 SQL 语句，返回受此语句影响的行数。比如增删改,
 *                  如果是查询PDO::exec() 不会从一条 SELECT 语句中返回结果。对于在程序中只需要发出一次的 SELECT 语句，
 *                  可以考虑使用 PDO::query()。对于需要发出多次的语句，可用 PDO::prepare() 来准备一个 PDOStatement 对象并用 PDOStatement::execute() 发出语句。
 *      PDO::quote() 为SQL语句中的字符串添加引号或者转义特殊字符串。
 *      public string PDO::quote ( string $string [, int $parameter_type = PDO::PARAM_STR ] )
 *          实例：
 *              $name = 'ddd';
                $name = $DB->quote($name);
                $sql = 'select * from first where name = '.$name;
                $result = $DB->query($sql);
                var_dump($result->fetchAll());
 *     PDO::query — 执行 SQL 语句，返回PDOStatement对象,可以理解为结果集
 *     PDO::getAttribute — 取回一个数据库连接的属性
 *          attribute
                PDO::ATTR_* 常量中的一个。下列为应用到数据库连接中的常量：
                •PDO::ATTR_AUTOCOMMIT
                •PDO::ATTR_CASE
                •PDO::ATTR_CLIENT_VERSION
                •PDO::ATTR_CONNECTION_STATUS
                •PDO::ATTR_DRIVER_NAME
                •PDO::ATTR_ERRMODE
                •PDO::ATTR_ORACLE_NULLS
                •PDO::ATTR_PERSISTENT
                •PDO::ATTR_PREFETCH
                •PDO::ATTR_SERVER_INFO
                •PDO::ATTR_SERVER_VERSION
                •PDO::ATTR_TIMEOUT
 *
 *     PDO::getAvailableDrivers — 返回一个可用驱动的数组
 *          $ds = $DB->getAvailableDrivers();
            var_dump($ds);
            array (size=2)
            0 => string 'mysql' (length=5)
            1 => string 'sqlite' (length=6)
 *      PDO::lastInsertId — 返回最后插入行的ID或序列值
 *              string PDO::lastInsertId ([ string $name = NULL ] )
 *      PDO::inTransaction — 检查是否在一个事务内
 *
 *
 *
 *
 */

try {
    $DB = new PDO('mysql:host=127.0.0.1;port=3306;dbname=shechunxiao;charset=UTF8;','root','', [
        PDO::ATTR_PERSISTENT=>false,
        PDO::ATTR_CASE=>PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//        PDO::ATTR_ORACLE_NULLS => false

    ]);
//    print $DB->errorCode();
//    print_r($DB->errorInfo());
//    $r = $DB->exec('update first set name= "ddd" where id=1');
//    var_dump($r);
//    $name = 'ddd';
//    $name = $DB->quote($name);
//    $sql = 'select * from first where name = '.$name;
//    print_r($sql);
//    echo '<br>';
//    $result = $DB->query($sql);
//    var_dump($result->fetchAll());
//    $ds = $DB->getAvailableDrivers();
//    var_dump($ds);
//    $DB->exec('insert into first (name) values ("fdsfdds")');
//    $id = $DB->lastInsertId();
//    print $id;

//    $isTrasaction = $DB->inTransaction();
//    print $isTrasaction;

//    print $DB->getAttribute(PDO::ATTR_AUTOCOMMIT).'<br/>'; //如果此值为 FALSE ，PDO 将试图禁用自动提交以便数据库连接开始一个事务。
//    print $DB->getAttribute(PDO::ATTR_CASE).'<br/>';
//    //用类似 PDO::CASE_* 的常量强制列名为指定的大小写。 可选的值有,PDO::CASE_NATURAL(integer) 保留数据库驱动返回的列名。PDO::CASE_LOWER(integer) 强制列名小写。
//    //PDO::CASE_UPPER(integer) 强制列名大写。
//    print $DB->getAttribute(PDO::ATTR_CLIENT_VERSION).'<br/>'; //此为只读属性；返回 PDO 驱动所用客户端库的版本信息。
//    print $DB->getAttribute(PDO::ATTR_CONNECTION_STATUS).'<br/>';
//    print $DB->getAttribute(PDO::ATTR_DRIVER_NAME).'<br/>'; //PDO::ATTR_DRIVER_NAME(string) 返回驱动名称。
//    print $DB->getAttribute(PDO::ATTR_ERRMODE).'<br/>';
//    //PDO::ATTR_ERRMODE(integer) 关于此属性的更多信息请参见 错误及错误处理部分。PDO::ERRMODE_SILENT  此为默认模式。 PDO 将只简单地设置错误码
//    //PDO::ERRMODE_WARNING ,除设置错误码之外，PDO 还将发出一条传统的 E_WARNING 信息
//
//    print $DB->getAttribute(PDO::ATTR_ORACLE_NULLS).'<br/>';
//    print $DB->getAttribute(PDO::ATTR_PERSISTENT).'<br/>'; //请求一个持久连接，而非创建一个新连接。
//    print $DB->getAttribute(PDO::ATTR_SERVER_INFO).'<br/>'; //此为只读属性。返回一些关于 PDO 所连接的数据库服务的元信息。
//    print $DB->getAttribute(PDO::ATTR_SERVER_VERSION).'<br/>'; //此为只读属性；返回 PDO 所连接的数据库服务的版本信息。
//    print $DB->getAttribute(PDO::ATTR_TIMEOUT).'<br/>'; //mysql不支持
//    print $DB->getAttribute(PDO::ATTR_PREFETCH).'<br/>';  //mysql不支持
//    $result = $DB->query('select * from first');
//    var_dump($result);
//    print_r($result->fetchAll());
} catch (PDOException $e) {

    print "Error44!: " . $e->getMessage() . "<br/>";
    die();
}