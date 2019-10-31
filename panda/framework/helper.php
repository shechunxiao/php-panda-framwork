<?php

if (!function_exists('my')){
    function my(){
        echo 'my';
    }
}

if (!function_exists('env')){
    function env($name=null){
        if (is_null($name)){
            return $_ENV;
        }
        return getenv($name);
    }
}