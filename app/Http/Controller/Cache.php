<?php

namespace App\Http\Controller;
class Cache
{
    protected $redis;
    protected $name;
    protected $default;

    public function __construct(Redis $redis,$name,$default='默认值')
    {
        $this->redis = $redis;
        $this->name = $name;
        $this->default = $default;
    }


}