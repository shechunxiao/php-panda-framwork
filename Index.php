<?php

require 'bootstrap/autoload.php';


try {
    $DB = new PDO('mysql:host=127.0.0.1;port=3306;dbname=shechunxiao;charset=UTF8;','root','', array(PDO::ATTR_PERSISTENT=>true));
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $result = $DB->query('select * from first22');
    var_dump($result);
    print_r($result->fetchAll());
} catch (PDOException $e) {

    print "Error44!: " . $e->getMessage() . "<br/>";
    die();
}