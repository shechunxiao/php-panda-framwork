<?php
namespace App\Http\Controller;
class RequestController{
    public function index(){
        $input = file_get_contents('php://input');
        $input = parse_str($input,$data);
        var_dump($data);
        $server = $_SERVER;
        var_dump($server);
        $get = $_GET;
        var_dump($get);
        $post = $_POST;
        var_dump($post);

    }





}