<?php
require 'bootstrap/autoload.php';

/**
 * 初始化测试
 */
//$container = new \App\Http\Controller\BaseContainer();
//$container->instance('second',new \App\Http\Controller\SecondController());
//$second = $container->get('second');
//var_dump($second);

/**
 * 第二部分测试
 */
//$defer = new \App\Http\Controller\DeferContainer();
//$defer->bind('second',function(){
//    return new \App\Http\Controller\SecondController();
//});
////获取服务
//$second = $defer->make('second');
//var_dump($second);

/**
 * 单例测试
 */
//$single = new \App\Http\Controller\SingletonContainer();
//$single->singleton('second',function(){
//    return new \App\Http\Controller\SecondController();
//});
//$second = $single->make('second');
//$second = $single->make('second');
//var_dump($second);

/**
 * 构造函数依赖注入（自动注入）测试
 */
//$inject = new \App\Http\Controller\InjectionContainer();
//$inject->singleton(\App\Http\Controller\Redis::class,function(){
//    return new \App\Http\Controller\Redis();
//});
////构建cache类
//$cache = $inject->make(\App\Http\Controller\Cache::class);
//var_dump($cache);

/**
 * 自定义依赖参数,也是构造函数的参数，但该参数不是类，是普通变量
 */
//$paramters = new \App\Http\Controller\ParametersContainer();
//$paramters->singleton(\App\Http\Controller\Redis::class,function(){
//    return new \App\Http\Controller\Redis();
//});
//$cache = $paramters->make(\App\Http\Controller\Cache::class,['name'=>'这里是名字','default'=>'哈哈']);
//var_dump($cache);

/**
 * 别名测试
 */
//$alias = new \App\Http\Controller\AliasContainer();
//$alias->instance('text','这里是一个字符串');
////给服务注册别名
//$alias->alias('string','text');
//$alias->alias('content','text');
//var_dump($alias->make('string'));
//var_dump($alias->make('content'));

/**
 * 扩展绑定测试
 */
//$extend = new \App\Http\Controller\ExtendContainer();
////单例实例化，也就是说只实例化一次
//$extend->singleton(\App\Http\Controller\Redis::class,function(){
//    return new \App\Http\Controller\Redis();
//});
//$extend->extend(\App\Http\Controller\Redis::class,function(\App\Http\Controller\Redis $redis){
//    $redis->setName('扩展器');
//    return $redis;
//});
//$redis = $extend->make(\App\Http\Controller\Redis::class);
//var_dump($redis);
//var_dump(\App\Http\Controller\Redis::class);

/**
 * 上下文绑定测试
 */

//$context = new \App\Http\Controller\ContextContainer();
//$context->when(\App\Http\Controller\Dog::class)->needs('name')->give('小狗');
//$context->when(\App\Http\Controller\Cat::class)->needs('name')->give('小猫');
//$dog = $context->make(\App\Http\Controller\Dog::class);
//$cat = $context->make(\App\Http\Controller\Cat::class);
//var_dump('Dog-'.$dog->name);
//var_dump('Cat-'.$cat->name);













