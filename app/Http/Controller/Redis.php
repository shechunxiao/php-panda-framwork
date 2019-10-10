<?php
namespace App\Http\Controller;
class Redis {
    public $name;
    public function __construct($name = 'default')
    {
        $this->name = $name;
    }
    public function setName($name){
        $this->name = $name;
    }

}