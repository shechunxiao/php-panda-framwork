<?php

if (!function_exists('my')){
    function my(){
        echo 'my';
    }
}

/**
 * 获取.env的配置
 */
if (!function_exists('env')){
    function env($name=null){
        if (is_null($name)){
            return $_ENV;
        }
        return getenv($name);
    }
}