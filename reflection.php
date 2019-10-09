<?php
require 'bootstrap/autoload.php';

$reflection = new ReflectionClass(\App\Http\Controller\SecondController::class);

var_dump($reflection->getFileName());
var_dump($reflection->getConstant('c'));
var_dump($reflection->getConstants());
var_dump($reflection->getDocComment());
var_dump($reflection->getStartLine());
var_dump($reflection->getEndLine());
var_dump($reflection->getExtension());
var_dump($reflection->getInterfaceNames());
var_dump($reflection->getInterfaces());
var_dump($reflection->getMethod('index')); //获取类中方法的对象
var_dump($reflection->getMethods()); //获取类中方法的对象
var_dump($reflection->getModifiers()); //获取修饰符
var_dump($reflection->getName()); //获取类名
var_dump($reflection->getNamespaceName()); //获取命名空间名称
var_dump($reflection->getParentClass()); //获取父类名称
var_dump($reflection->getShortName()); //获取不含命名空间的类名
var_dump($reflection->getTraitNames()); //获取trait类名称
var_dump($reflection->getTraits()); //获取trait类数组
var_dump($reflection->hasConstant('d'));
var_dump($reflection->hasMethod('index'));
var_dump($reflection->hasProperty('b'));
//var_dump($reflection->implementsInterface('b'));
var_dump($reflection->inNamespace());
var_dump($reflection->isAbstract());
var_dump($reflection->isCloneable());
var_dump($reflection->isFinal());
var_dump($reflection->isInstantiable());
var_dump($reflection->isInternal());
var_dump($reflection->isSubclassOf(\App\Http\Controller\FirstController::class));
var_dump($reflection->__toString());
//$without = $reflection->newInstanceWithoutConstructor(); //实例化一个类，并且不调用它的构造函数
//$without->index();
//if ($reflection->isInstantiable()){
//    $instance = $reflection->newInstance(1,2);
//}
//var_dump($instance);
//$instance->index();
//方法反射
$methodReflection = $reflection->getMethod('test');
var_dump($methodReflection->getModifiers());
var_dump($methodReflection->isAbstract());
var_dump($methodReflection->isFinal()); //使用final关键字标记的方法不能被子类覆盖
var_dump($methodReflection->isProtected());
var_dump($methodReflection->isPublic());
var_dump($methodReflection->isPrivate());
var_dump($methodReflection->isStatic());
var_dump($methodReflection->isDestructor());
var_dump($methodReflection->isConstructor());
var_dump($methodReflection->isClosure());
//$methodReflection->invoke();
echo '******************************* Params ****************************************';
$construct = $reflection->getConstructor();
$params = $construct->getParameters();
//var_dump($construct);
//var_dump($params);
foreach ($params as $param){
    var_dump($param->getClass());
    var_dump($param->getClass()->name);
}



