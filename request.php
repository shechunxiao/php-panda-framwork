<?php
require 'bootstrap/autoload.php';
use App\Http\Controller\RequestController;

//这里牵扯到路由的问题，所以先封装路由
$request = new RequestController();
$request->index();