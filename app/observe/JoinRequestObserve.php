<?php


class JoinRequestObserve implements Observable
{

    function inserted($model){}

    /**
     * @param $model JoinRequest
     */
    function updated($model){
        if ($model->accept){
            $employee = new Employs();
            $employee->group_id = $model->group_id;
            $employee->user_id = $model->user_id;
            $employee->boss_id = $model->boss_id;
            $employee->title = $model->title;
            $employee->save();
            Notification::send(User::Query()->getById($model->boss_id) , "در خواست عضویت شما را قبول کرد");
        }else{
            Notification::send(User::Query()->getById($model->boss_id) , "در خواست عضویت شما را رد کرد");
        }

        $model->delete();
    }

    function deleted($model){}
}