<?php


class ToDoUpdateValidator implements Middleware
{

    private ToDo $todo;

    function initParameters($params)
    {}

    function init(ToDo $toDo){
        $this->todo = $toDo;
    }

    function authorize(Request $request)
    {
        if (! $this->todo->mine()){
            JsonOutput::error("شما قادر به ویرایش نیستید")->throw();
            return false;
        }
        return true;
    }

    function handle(Request $request)
    {
        return Validator::check($request ,
            [
                "title"    =>["string" , "strlenbetween:3,100"],
                "description"    =>["string" , "strlenbetween:3,500"],
                "picture" => ["file:image/" , "maxsize:1000000"],
                "file" => ["file:image/" , "maxsize:10000000"]
            ],
            [
                "title"          => "تایتل باید بین ۳ تا ۱۰۰ کاراکتر باشد",
                "description"    => "توضیحات باید بین ۳ تا ۵۰۰ کاراکتر باشد",
                "picture"        => "فایل تصویر باید کمتر از ۱ مگ باشد",
                "file"           => "فایل پیوند باید تا کمتر از ۱۰ مگ باشد"
            ] , true)->getStatus();
    }
}