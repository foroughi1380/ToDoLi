<?php


class GroupOrgObserve implements Observable
{
    /**
     * @param GroupOrg $model
     */
    function inserted($model){
        $employee = new Employs();
        $employee->user_id = $model->user_id;
        $employee->title = "رئیس کل";
        $employee->group_id = $model->id;
        $employee->save();
    }

    function updated($model){}

    /**
     * @param GroupOrg $model
     */
    function deleted($model)
    {

    }
}