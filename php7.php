<?php
//declare(strict_types=1);
require 'bootstrap/autoload.php';

/**
 * 理解PHP7新特性
 */

/**
 * PHP 标量类型与返回值类型声明
 */
//$a = (int)'1';
//$a = '1';
//var_dump($a);
//$b1 = (int)1.1;
//$b2 = (int)1;
//$b3 = (int)1.9;
//var_dump($b1);
//var_dump($b2);
//var_dump($b3);

//function sum(int ...$args){
//    var_dump($args);
//    return array_sum($args);
//
//}
//print_r(sum(1,2,3,4,5.9));

//$ccc = [1,2,3,4,5];
//var_dump($ccc);

//function returnIntValue(int $value): int
//{
//    return $value;
//}
//
//print(returnIntValue(5));

/**
 * PHP NULL 合并运算符
 * PHP 7 新增加的 NULL 合并运算符（??）是用于执行isset()检测的三元运算的快捷方式。

NULL 合并运算符会判断变量是否存在且值不为NULL，如果是，它就会返回自身的值，否则返回它的第二个操作数。

以前我们这样写三元运算符：

$site = isset($_GET['site']) ? $_GET['site'] : '菜鸟教程';
现在我们可以直接这样写：

$site = $_GET['site'] ?? '菜鸟教程';
 */
//$a = 1;
//$iss = $a??'323432';
//var_dump($iss);

/**
 * 太空船运算符
 * PHP 7 新增加的太空船运算符（组合比较符）用于比较两个表达式 $a 和 $b，如果 $a 小于、等于或大于 $b时，它分别返回-1、0或1。
 */
//var_dump(1<=>1);
//var_dump(1<=>2);
//var_dump(2<=>1);

//var_dump(1.5<=>1.5);
//var_dump(1.5<=>2.5);
//var_dump(2.5<=>1.5);

//var_dump('a'<=>'a');
//var_dump('a'<=>'b');
//var_dump('b'<=>'a');

/**
 * 常量数组
 * 在 PHP 5.6 中仅能通过 const 定义常量数组，PHP 7 可以通过 define() 来定义。
 */
//define('arr',[
//   'name'=>'张三',
//   'age'=>18
//]);
//var_dump(arr);

/**
 * PHP 7 支持通过 new class 来实例化一个匿名类，这可以用来替代一些"用后即焚"的完整类定义。
 */
//interface Logger {
//    public function log(string $msg);
//}
//
//class Application {
//    private $logger;
//
//    public function getLogger(): Logger {
//        return $this->logger;
//    }
//
//    public function setLogger(Logger $logger) {
//        $this->logger = $logger;
//    }
//}
//
//$app = new Application;
//// 使用 new class 创建匿名类
//$app->setLogger(new class implements Logger {
//    public function log(string $msg) {
//        print($msg);
//    }
//});
//
//$app->getLogger()->log("我的第一条日志");

/**
 * PHP Closure::call()
 * PHP 7 的 Closure::call() 有着更好的性能，将一个闭包函数动态绑定到一个新的对象实例并调用执行该函数。
 */
//class A{
//
//}
//$getX = function(){
//    return 1111;
//};
//echo $getX->call(new A());

/**
 * PHP 过滤 unserialize()
 * 这个反序列化的第二个参数旨在通过添加白名单的方式实现类的反序列化。
 * PHP 7 增加了可以为 unserialize() 提供过滤的特性，可以防止非法数据进行代码注入，提供了更安全的反序列化数据。
 */
//class MyClass1 {
//    public $obj1prop;
//}
//class MyClass2 {
//    public $obj2prop;
//}
//
//
//$obj1 = new MyClass1();
//$obj1->obj1prop = 1;
//$obj2 = new MyClass2();
//$obj2->obj2prop = 2;
//
//$serializedObj1 = serialize($obj1);
//$serializedObj2 = serialize($obj2);
//
//$data = unserialize($serializedObj1,['allowed_classes'=>['MyClass2']]);
//echo $data->obj1prop;
//var_dump($data);

/**
 * CSPRNG（Cryptographically Secure Pseudo-Random Number Generator，伪随机数产生器）。

PHP 7 通过引入几个 CSPRNG 函数提供一种简单的机制来生成密码学上强壮的随机数。

random_bytes() - 加密生存被保护的伪随机字符串。

random_int() - 加密生存被保护的伪随机整数。
 */
//var_dump(bin2hex(random_bytes(5)));
//var_dump(random_int(1,1000));

/**
 * PHP 7 use 语句
PHP 7 新特性 PHP 7 新特性

PHP 7 可以使用一个 use 从同一个 namespace 中导入类、函数和常量：

实例
实例
// PHP 7 之前版本需要使用多次 use
use some\namespace\ClassA;
use some\namespace\ClassB;
use some\namespace\ClassC as C;

use function some\namespace\fn_a;
use function some\namespace\fn_b;
use function some\namespace\fn_c;

use const some\namespace\ConstA;
use const some\namespace\ConstB;
use const some\namespace\ConstC;

// PHP 7+ 之后版本可以使用一个 use 导入同一个 namespace 的类
use some\namespace\{ClassA, ClassB, ClassC as C};
use function some\namespace\{fn_a, fn_b, fn_c};
use const some\namespace\{ConstA, ConstB, ConstC};
?>
 */

/**
 * PHP7的错误处理
 *
 */
//class MathOperations
//{
//    protected $n = 10;
//
//    // 求余数运算，除数为 0，抛出异常
//    public function doOperation(): string
//    {
//        try {
//            $value = $this->n % 0;
//            return $value;
//        } catch (DivisionByZeroError $e) {
//            return $e->getMessage();
//        }
//    }
//}
//
//$mathOperationsObj = new MathOperations();
//print($mathOperationsObj->doOperation());

/**
 * PHP intdiv() 函数，两个参数先取整,再除相除，再取整
 */
//echo intdiv(9,1.6),PHP_EOL;
//echo intdiv(9,2),PHP_EOL;
//echo intdiv(9,1.1),PHP_EOL;














