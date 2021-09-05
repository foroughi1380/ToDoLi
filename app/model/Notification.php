<?php


class Notification extends Model
{
    var $user_id;
    var $sender_id;
    var $message;



    static function send(User $to , $message , User $from = null){
        if ($from == null){
            $from = Auth::getUser();
        }

        $notification = new Notification();
        $notification->user_id = $to->id;
        $notification->sender_id = $from->id;
        $notification->message = $message;
        $notification->save();
    }
}