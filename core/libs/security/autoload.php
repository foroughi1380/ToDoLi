<?php
function getSecurityPath($path){
    return __DIR__ . "/$path";
}

include_once getSecurityPath("Auth.php");
include_once getSecurityPath("Guard.php");