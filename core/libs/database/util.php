<?php
/**
 * @param string $name
 * @param Request $req
 * @return Model|null
 */
function core_database_getModelInstance($name , $req){
    include_once __DIR__ . "/../../../app/model/$name.php";
    $id = 0;
    if($req->hasKey($name::$requestIdName)){
        $id = $req->get($name::$requestIdName);
    }else if ($req->hasKey("id")){
        $id = $req->get("id");
    }else{
        return null;
    }
    return $name::Query()->getById($id);
}

$core_db = null;
function core_getDatabase(){
    global $core_db;
    if ($core_db == null){
        $core_db = new DataBase();
    }
    return $core_db;
}

function core_createTables($db , $drop = true){
    if ($db == null){$db = core_getDatabase();}

    include_once __DIR__ . "/../../../config/database/tables.php";
    foreach ($core_database_tables as $name => $columns){
        if ($drop){
            $db->execute("drop table IF exists $name");
        }
        $db->execute(core_create_table_sql($name , $columns));
    }
}

function core_create_table_sql($name , $columns){
    $ret = "create table $name( ";
    $ret .= "`id` INT  PRIMARY KEY  AUTO_INCREMENT , ";
    foreach ($columns as $column){
        $ret .= "$column , ";
    }
    $ret .= "`createdat` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , ";
    $ret .= "`updatedat` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , ";
    $ret .= "`deleted` BOOLEAN NOT NULL DEFAULT FALSE";
    $ret .= ")";

    return $ret;
}

function core_get_model_observe($model){
    global $core_database_observe;
    if (isset($core_database_observe[$model])){
        include_once __DIR__ . "/../../../app/observe/{$core_database_observe[$model]}.php";
        return new $core_database_observe[$model];
    }
    return null;
}