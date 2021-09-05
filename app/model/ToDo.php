<?php


class ToDo extends Model
{
    public static string $requestIdName = "todo_id";

    var $picture;
    var $file;
    var $description;
    var $title;
    var $answerFile;
    var $extra;
    var $answered;
    var $user_id;
    var $owner_id;
    var $group_id;


    function mine(){
        return $this->owner_id == Auth::getUser()->id;
    }

    function isMyTodo(){
        return $this->user_id == Auth::getUser()->id;
    }


    function sendMessage($message){
        $chat = new Chat();
        $chat->user_id = Auth::getUser()->id;
        $chat->todo_id = $this->id;
        $chat->message = $message;
        $chat->save();

        return $chat;
    }

    function getMessages(){
        return Chat::Query()->andWhere("todo_id" , "=" , $this->id)->get();
    }

    function canSendMessage(){
        return $this->owner_id == Auth::getUser()->id || $this->user_id == Auth::getUser()->id;
    }
}