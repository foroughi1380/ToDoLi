<?php
    include_once "../core/autoload.php";
    $database = new DataBase(true);
    core_createTables($database , true);
    $database->commit();
    unset($database);
    echo "finish";






//$table_name= "notification";
//    include "../config/database/tables.php";
//    global  $core_database_tables;
//    $database = new DataBase(false);
//    $database->execute("drop table if exists $table_name");
//    $database->execute(core_create_table_sql($table_name , $core_database_tables[$table_name]));
//
//$database->commit();
//    unset($database);
//    echo "finish";
