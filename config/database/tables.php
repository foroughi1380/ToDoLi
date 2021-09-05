<?php

$core_database_tables = [
  "user"=>[
      "name text not null",
      "family text not null",
      "username text not null",
      "password text not null",
      "email text not null",
      "address text",
      "picture text"
    ],

    "contactusmessage" => [
        "name varchar(31) not null",
        "email varchar(150) not null",
        "message varchar(151) not null",
    ],

    "grouporg" => [
        "name varchar(31) not null",
        "user_id int not null",
        "description varchar(150) not null",
    ],

    "employs" => [
        "boss_id int",
        "user_id int not null",
        "group_id int not null",
        "title varchar(150) not null",
    ],
    "joinrequest" => [
        "group_id int not null",
        "boss_id int not null",
        "user_id int not null",
        "accept BOOLEAN",
        "title varchar(150) not null",
    ],
    "todo" => [
        "title varchar(100) not null",
        "description text not null",
        "picture varchar(150)",
        "file varchar(150)",
        "answerFile varchar(150)",
        "extra text",
        "owner_id int not null",
        "user_id int not null",
        "group_id int not null",
        "answered BOOLEAN not null default false",
    ],
    "chat" => [
        "message varchar(100) not null",
        "user_id int not null",
        "todo_id int not null",
    ],
    "notification" => [
        "message text not null",
        "user_id int not null",
        "sender_id int not null",
    ]
];
