<?php
require 'bootstrap/autoload.php';


try {
    $DB = new PDO('mysql:host=127.0.0.1;port=3306;dbname=shechunxiao;charset=UTF8;','root','', [
        PDO::ATTR_PERSISTENT=>true
    ]);
    print $DB->getAttribute(PDO::ATTR_AUTOCOMMIT).'<br/>';
    print $DB->getAttribute(PDO::ATTR_CASE).'<br/>';
    print $DB->getAttribute(PDO::ATTR_CLIENT_VERSION).'<br/>';
    print $DB->getAttribute(PDO::ATTR_CONNECTION_STATUS).'<br/>';
    print $DB->getAttribute(PDO::ATTR_DRIVER_NAME).'<br/>';
    print $DB->getAttribute(PDO::ATTR_ERRMODE).'<br/>';
    print $DB->getAttribute(PDO::ATTR_ORACLE_NULLS).'<br/>';
    print $DB->getAttribute(PDO::ATTR_PERSISTENT).'<br/>';
    print $DB->getAttribute(PDO::ATTR_SERVER_INFO).'<br/>';
    print $DB->getAttribute(PDO::ATTR_SERVER_VERSION).'<br/>';
//    print $DB->getAttribute(PDO::ATTR_TIMEOUT).'<br/>'; //mysql不支持
//    print $DB->getAttribute(PDO::ATTR_PREFETCH).'<br/>';  //mysql不支持
    $result = $DB->query('select * from first');
    var_dump($result);
    print_r($result->fetchAll());
} catch (PDOException $e) {

    print "Error44!: " . $e->getMessage() . "<br/>";
    die();
}