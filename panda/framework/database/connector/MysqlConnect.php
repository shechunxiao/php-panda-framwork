<?php
namespace Panda\database\connector;

class MysqlConnect extends Connect {

    public function getConnect(){
        return $this->connect();
    }



}