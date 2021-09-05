<?php
include_once "../config/actions.php";
include_once "../config/middlewares.php";
include_once "../core/autoload.php";
$req = new Request();

core_checkValidMethod($req);

core_callAction($core_actions[$req->action] , isset($core_middlewares[$req->action]) ?  $core_middlewares[$req->action]  : [], $req);


