<?php

require 'bootstrap/autoload.php';
error_reporting(0);
register_shutdown_function( 'appShutdown');
set_error_handler( 'appError',E_ALL ^ E_NOTICE);
set_exception_handler( 'appException');


function appError($a,$b,$c,$d,$e){
    echo 'appError';
    $err = error_get_last();
    var_dump($err);
    var_dump($a);
    var_dump($b);
    var_dump($c);
    var_dump($d);
    var_dump($e);
}
function appException($e2){
    echo 'appException';
    $err = error_get_last();
    var_dump($e2);
    var_dump($err);
    var_dump($e2->getMessage());
}
function appShutdown(){
    echo 'appShutdown';
    $this->appException();
}

//throw new Exception('Uncaught Exception occurred');
//$a = m;
//fdkfjklsdflksdfk();
//$b = new sfsd();
require 'fdsfsd.txt';
