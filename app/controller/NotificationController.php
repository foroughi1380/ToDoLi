<?php


class NotificationController
{
    function index(){
        return JsonOutput::value(Notification::Query()->andWhere("user_id" , "=" , Auth::getUser()->id)
            ->join(User::class , 'id' , 'sender_id')
            ->get());
    }
}