<?php

namespace App\Controller;


class DemoOne implements Demo {
    protected $levelTwo;
    public function __construct(LevelTwo $levelTwo)
    {

        $this->levelTwo = $levelTwo;
    }
    public function index(){
        echo 'DemoOne//';
    }
    public function getLevel(){
        return $this->levelTwo;
    }
}