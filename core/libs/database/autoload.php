<?php
function getDatabasePath($path){
    return __DIR__ . "/$path";
}

include_once getDatabasePath("../../../config/database/observe.php");
include_once getDatabasePath("Observable.php");
include_once getDatabasePath("util.php");
include_once getDatabasePath("Model.php");

core_includeAllFileInDirectory("app/model");

include_once getDatabasePath("DataBase.php");
include_once getDatabasePath("Query.php");
