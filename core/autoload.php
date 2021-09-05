<?php

function getPath($path){
    return __DIR__ . "/libs/$path";
}


include_once getPath('core_util.php');
include_once getPath('JsonOutput.php');
include_once getPath('callActionController.php');
include_once getPath('Middleware.php');
include_once getPath('Request.php');
include_once getPath('Validator.php');

include_once getPath('database/autoload.php');
include_once getPath('security/autoload.php');
include_once getPath('def/autoload.php');

include_once __DIR__ . "/../config/storage/config.php";

