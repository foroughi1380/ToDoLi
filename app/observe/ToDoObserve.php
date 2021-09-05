<?php


class ToDoObserve implements Observable
{
    /**
     * @param ToDo $model
     */
    function inserted($model)
    {
        Notification::send(User::byId($model->user_id) , "یک کار توسط رئیس برای شما ایجاد شد");
    }

    /**
     * @param ToDo $model
     */
    function updated($model)
    {
        if ($model->answered == 0){
            Notification::send(User::byId($model->user_id) , "یک کار توسط رئیس ویرایش شد");
        }
    }

    /**
     * @param ToDo $model
     */
    function deleted($model)
    {
        if ($model->answered == 0){
            Notification::send(User::byId($model->user_id) , "یک کار توسط رئیس حذف شد");
        }else{
            Notification::send(User::byId($model->owner_id) , "یک کار توسط کارمند شما تمام شد.");
        }
    }
}