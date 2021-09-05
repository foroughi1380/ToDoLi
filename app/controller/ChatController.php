<?php


class ChatController
{
    function index(ToDo $toDo){
        if (! $toDo->canSendMessage()) JsonOutput::error("شما قادر به دریافت چت نمیباشید")->throw();
        return JsonOutput::value($toDo->getMessages());
    }


    function store(ToDo $toDo , Request $request){
        if (! $toDo->canSendMessage() || !$request->hasKey("message")) JsonOutput::error("شما قادر به ارسال پیام نمیباشید")->throw();
        return JsonOutput::value($toDo->sendMessage($request->get("message")));
    }
}