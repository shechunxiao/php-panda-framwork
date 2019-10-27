<?php

namespace App\Controller;


class DemoTwo implements Demo {
    public function __construct($a,$b,$c)
    {
        var_dump($a.'---23423432432');
        var_dump($b.'---23423432432');
        var_dump($c.'---23423432432');
    }

    public function index(){
        echo 'demotwo';
    }
}