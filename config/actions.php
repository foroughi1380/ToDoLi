<?php
$core_actions = [
    "ali"       => array("GET"  , "mandali" , "index"),
    "me"       => array("GET"  , "UserController" , "index"),
    "register" => array("POST" , "UserController" , "register"),
    "login"    => array("POST" , "UserController" , "login"),
    "logout"   => array("GET"  , "UserController" , "logout"),
    "profileupdate"   => array("POST"  , "UserController" , "update"),

    "contactus"=> array("POST" , "ContactUsController" , "store"),

    "groups"      => array("GET" , "GroupController" , 'index'),
    "groupadd"   => array("POST" , "GroupController" , 'store'),
    "groupupdate"=> array("POST" , "GroupController" , 'update'),
    "groupdelete"=> array("GET" , "GroupController" , 'delete'),


    "joinrequest" => array("GET" , "JoinRequestController" , "store"),
    "joinrequestaccept" => array("GET" , "JoinRequestController" , "update"),
    "joinlist" => array("GET" , "JoinRequestController" , "index"),


    "groupchart" => array("GET" , "EmployeeController" , "index"),



    "todolist" => array("GET" , "ToDoController" , "index"),
    "usertodolist" => array("GET" , "ToDoController" , "employeeIndex"),
    "definisiontodo" => array("POST" , "ToDoController" , "store"),
    "updatetodo" => array("POST" , "ToDoController" , "update"),
    "deletetodo" => array("POST" , "ToDoController" , "delete"),
    "answertodo" => array("POST" , "ToDoController" , "answer"),

    "chatsendmessage" => array("POST" , "ChatController" , "store"),
    "chatmessages" => array("GET" , "ChatController" , "index"),


    "notifications" => array("GET" , "NotificationController" , "index"),


];
