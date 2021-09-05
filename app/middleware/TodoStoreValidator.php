<?php


class TodoStoreValidator implements Middleware
{
    private GroupOrg $group;
    private User $user;

    function initParameters($params){}

    function init(GroupOrg $group , User $user){
        $this->user = $user;
        $this->group = $group;
    }

    function authorize(Request $request)
    {
        if (! $this->user->isYourBoss($this->group , Auth::getUser())){
            JsonOutput::error("شما نمیتوانید برای شخص مورد نظر کار ایجاد کنید.")->throw();
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
            "title"    =>["require" , "string" , "strlenbetween:3,100"],
            "description"    =>["require" , "string" , "strlenbetween:3,500"],
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