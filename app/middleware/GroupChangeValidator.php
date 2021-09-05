<?php


class GroupChangeValidator implements Middleware
{

    private GroupOrg $group;

    function initParameters($params){}

    function init(GroupOrg $group){
        $this->group = $group;
    }

    /**
     * @inheritDoc
     */
    function authorize(Request $request){
        if (! $this->group->canEdit()){
            echo JsonOutput::error("شما به این قابلیت دسترسی ندارید");
            return false;
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    function handle(Request $request)
    {
        return Validator::check($request ,
            [
                "name"        => ["string" , "strlenbetween:3,20"],
                "description" => ["string" , "srtlenbetween:3,150"]
            ] ,
            [
                "name" => "نام باید بین ۳ تا ۲۰ کاراکتر باشد",
                "description" => "توضیحات باید بین ۳ تا ۱۵۰ کاراکتر باشد"
            ] , true)->getStatus();
    }
}