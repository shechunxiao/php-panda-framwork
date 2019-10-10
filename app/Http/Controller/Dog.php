<?php

namespace App\Http\Controller;
class Dog
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}