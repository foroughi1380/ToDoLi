<?php
$core_middlewares = [
    "me" => array("Guard"),
    "register" => array("registerUserValidator:hello,action"),
    "login" => array("loginUserValidator"),
    "profileupdate" => array("Guard" , "ProfileUpdateValidator"),

    "contactus" => array("ContactUsValidator"),

    "groups" => array("Guard:def,index.html"),
    "groupadd" => array("Guard" , "GroupValidator"),
    "groupupdate" => array("Guard" , "GroupChangeValidator"),
    "groupdelete" => array("Guard" , "GroupChangeValidator"),


    "joinrequest" => array("Guard" , "JoinRequestValidator"),
    "joinrequestaccept" => array("Guard" , "JoinRequestAcceptValidator"),
    "joinlist" => array("Guard"),


    "todolist" => array("Guard"),
    "usertodolist" => array("Guard"),
    "definisiontodo" => array("Guard" , "TodoStoreValidator"),
    "answertodo" => array("Guard" , "ToDoAnswerValidator"),
    "updatetodo" => array("Guard" , "ToDoUpdateValidator"),
    "deletetodo" => array("Guard"),


    "notifications" => array("Guard"),

    "export" => array("Guard")
];
