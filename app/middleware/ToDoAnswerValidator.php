<?php


class ToDoAnswerValidator implements Middleware
{
    private ToDo $todo;
    function initParameters($params)
    {}

    function init(ToDo $todo){
        $this->todo = $todo;
    }

    function authorize(Request $request)
    {
        if (! $this->todo->isMyTodo()){
            JsonOutput::error("شما قادر به تایید نیستید")->throw();
            return false;
        }
        return true;

    }

    function handle(Request $request)
    {
        return Validator::check($request ,
            [
                "answerFile" => ["file" , "maxsize:10000000"]
            ],
            [
                "answerFile"=> "فایل پیوند باید تا کمتر از ۱۰ مگ باشد"
            ] , true)->getStatus();
    }
}