<?php
/**
 * 引入composer的autoload加载机制，实现类的自动加载
 */
require dirname(__DIR__) . '/bootstrap/autoload.php';

$app = new \Panda\foundation\Application(dirname(__DIR__));

//var_dump($app);
//var_dump($app->getInstance('application'));


//$app->bind(\App\Controller\Demo::class,function(){
//   return new \App\Controller\TestController();
//});
//$app->alias(\App\Controller\TestController::class,'test')->alias(\App\Controller\TestController::class,'fdfd')->bind(\App\Controller\TestController::class);
//$get = $app->getInstance(\App\Controller\TestController::class);
//var_dump($get);

//$instance = $app->instanceByReflection(\App\Controller\TestController::class);
//var_dump($instance);

//$app->bind(\App\Controller\FirstController::class);
//$first = $app->instance(\App\Controller\FirstController::class);
//var_dump($first);

//$app->bind(\App\Controller\Demo::class,\App\Controller\DemoController::class);
//
//$demo = $app->instance(\App\Controller\Demo::class);
//var_dump($demo);
//$demo->index();

//$app->bind(\App\Controller\FirstController::class);
//
//$first = $app->instance(\App\Controller\FirstController::class);
//var_dump($first);
//$first->index();

//$app->bind(\App\Controller\Demo::class,function(){
//    return new \App\Controller\DemoController();
//});
//
//$first = $app->instance(\App\Controller\Demo::class);
//var_dump($first);
//$first->index();

//$app->bind(\App\Controller\DemoFirst::class);
//$app->bind(\App\Controller\DemoSecond::class);
//$app->bind(\App\Controller\DemoOne::class);
//$app->bind(\App\Controller\DemoTwo::class);
//$app->bind(\App\Controller\LevelTwo::class);
//$app->contexts(\App\Controller\DemoFirst::class,\App\Controller\Demo::class,\App\Controller\DemoOne::class);
//$app->contexts(\App\Controller\DemoSecond::class,\App\Controller\Demo::class,\App\Controller\DemoTwo::class);
//var_dump($app);
//$demofirst = $app->instance(\App\Controller\DemoFirst::class);
//var_dump($demofirst);
//$demo = $demofirst->getDemo();
//var_dump($demo);
//$level = $demo->getLevel();
//var_dump($level);
//$level->index();
//var_dump($app);
//$i = $app->getInstance(\App\Controller\DemoFirst::class);
//var_dump($i);


//$app->bind(\App\Controller\Demo::class,function($a,$b,$c){
//    return new \App\Controller\DemoTwo($a,$b,$c);
//});
////var_dump($app);
//$app->alias(\App\Controller\Demo::class,'ali');
////$instance = $app->instance(\App\Controller\Demo::class,['a'=>1,'b'=>2,'c'=>3]);
//$instance = $app->instance('ali',['a'=>1,'b'=>2,'c'=>3]);
//var_dump($instance);


//$factory = new Dotenv\Environment\DotenvFactory([
//    new Dotenv\Environment\Adapter\EnvConstAdapter()
//]);
//$dotenv = Dotenv\Dotenv::create(dirname(__DIR__), null, $factory);
//$dotenv->load();
//
//$a = getenv('DB_DATABASE');
//var_dump($a);
//var_dump($_ENV);

//$dotenv = Dotenv\Dotenv::create(dirname(__DIR__));
//$dotenv->load();
//var_dump(getenv('DB_DATABASE'));
//var_dump($_ENV);

