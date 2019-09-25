<?php
namespace App\Http\Controller;
use Throwable;

class PdoException extends \PDOException {
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    public function __toString()
    {
        return 'fsdfds';
    }
    public function customFunction() {
        echo "pdo";
    }


}