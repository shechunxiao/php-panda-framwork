<?php
namespace Panda\database\connector;

class MysqlConnect extends Connect {

    public function getConnect(){
//        var_dump($this->connect());
        return $this->connect();
    }



}