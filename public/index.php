<?php
/**
 * 引入composer的autoload加载机制，实现类的自动加载
 */
require dirname(__DIR__) . '/bootstrap/autoload.php';

$app = new \Panda\foundation\Application(dirname(__DIR__));

//研究路由的实现以及Exception报错和日志系统的实现方式

/**
 * 基本构建思路:
 *      1.服务提供者，必须有一个基类,所有的关键服务核心类都继承这个类
 *      2.利用http-foundation 扩展实现http请求的处理
 *      3.执行各个服务的boot方法，启动，原来只是注册
 *      4.通过管道模式分发请求到执行的控制器和方法
 *      5.最后用echo $content输出内容到浏览器上
<?php
/**
 * 引入composer的autoload加载机制，实现类的自动加载
 */
require dirname(__DIR__) . '/bootstrap/autoload.php';

$app = new \Panda\foundation\Application(dirname(__DIR__));

//研究路由的实现以及Exception报错和日志系统的实现方式

/**
 * 基本构建思路:
 *      1.服务提供者，必须有一个基类,所有的关键服务核心类都继承这个类
 *      2.利用http-foundation 扩展实现http请求的处理
 *      3.执行各个服务的boot方法，启动，原来只是注册
 *      4.通过管道模式分发请求到执行的控制器和方法
 *      5.最后用echo $content输出内容到浏览器上
 */

$request = new \Panda\http\Request();
$request->init();
