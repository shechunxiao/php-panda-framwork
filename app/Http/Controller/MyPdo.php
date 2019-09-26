<?php
namespace App\Http\Controller;
use Throwable;

class MyPdo extends \PDOException {
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}