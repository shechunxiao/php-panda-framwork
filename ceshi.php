<?php

require 'bootstrap/autoload.php';
error_reporting(0);

set_error_handler('self', E_ALL | E_STRICT);
function self ($error_no, $error_str, $error_file, $error_line) {
    echo '自定义错误函数';
//    echo "erro_no: " . $error_no . " error_str: " . $error_str . PHP_EOL;
    //注意 程序并不会在这里退出执行
    //注意 如果返回了 false 错误会被 php 标准错误处理流程处理
}

try {
//    echo 1111
//    tfsfs();
//    include 'tesfs.txt';
//    $a = m;
//    $b - new ss();
    
} catch (\Exception $exception) {
    echo 22222222;
    var_dump($exception);
} catch (\Error $error) {
    // 就是这里了，try catch 捕捉了 Error
    echo 22222222222222;
    var_dump($error);
    if ($error instanceof Error){
        echo '属于error';
    }
}


