<?php

function core_callAction($action,$middlewares , $req){
    $appDir = __DIR__ . "/../../app/";

    include_once $appDir . "/controller/".$action[1] . ".php";

    foreach ($middlewares as $middleware) {
        $data = explode( ":" , $middleware , 2);
        $name = trim($data[0]);

        global $core_internal_middleware;
        if (! in_array($name , $core_internal_middleware)){
            include_once $appDir . "/middleware/" . $name . ".php";
        }
        $m = new $name;

        $params = [];

        if (isset($data[1])){
            $params = explode("," , $data[1]);
        }

        $m->initParameters($params);

        if (core_object_has_method($m , "init")){
            core_callMethod($m , "init" , $req);
        }

        if (!($m->authorize($req) && $m->handle($req))) {
            return;
        }
    }

    echo core_callMethod(new $action[1] , $action[2] , $req);
}

function core_callMethod($object , $method , $req){
    $ref = new ReflectionMethod($object , $method);
    $args = [];

    foreach ($ref->getParameters() as $parameter){

        switch ($parameter->getType()){

            case Request::class:
                $args[] = $req;
                break;


            default:
                $model = core_database_getModelInstance($parameter->getType() . "" , $req);
                if ($model === null){
                    $out = new JsonOutput();
                    $out->addErrors("Page not found");
                    $out->setStatus(false);
                    core_setResponseCode(404);
                    exit($out);
                }

                $args[] = $model;

                break;
        }

    }

    return $ref->invokeArgs($object , $args);
}
function core_object_has_method($obj , $method){
    $r= new ReflectionObject($obj);
    return $r->hasMethod($method);
}