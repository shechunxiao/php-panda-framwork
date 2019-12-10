<?php

namespace Panda\http;

use Symfony\Component\HttpFoundation\Request as SymRequest;
use Symfony\Component\HttpFoundation\Response;

class Request extends SymRequest
{
    public function init(){
        $request = Request::createFromGlobals();
//        $ser = $request->server;
//        $ser = $request->query;
        $ser = $request->request;
//        $ser = $request->files;
//        $ser = $request->files;
//        $ser = $request->query->all();
        var_dump($ser);
//        $ser = $request->query->keys('name');
        $ser = $request->query->get('name');
        $ser = $request->query->getAlpha('name');
        $ser = $request->getContent();
        $ser = $request->getPathInfo();
        var_dump($ser);
        $response = new Response('fjdsfjksdjkfkldlkslkfdsds');
        $response->send();

    }

}