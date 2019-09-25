<?php
namespace App\Http\Controller;
use Throwable;

class Exception extends \Exception{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    public function __toString()
    {
        return 'fsdfds';
    }
    public function customFunction() {
        echo "A custom function for this type of exception\n";
    }


}