<?php


class GroupValidator implements Middleware
{

    function initParameters($params){}

    /**
     * @inheritDoc
     */
    function authorize(Request $request){
//        if ($request->method !== "groups"){
//
//        }
        return true;
    }

    /**
     * @inheritDoc
     */
    function handle(Request $request)
    {
        return Validator::check($request ,
            [
                "name"        => ["require" , "string" , "strlenbetween:3,20"],
                "description" => ["require" , "string" , "strlenbetween:3,150"]
            ] ,
            [
                "name" => "نام باید بین ۳ تا ۲۰ کاراکتر باشد",
                "description" => "توضیحات باید بین ۳ تا ۱۵۰ کاراکتر باشد"
            ] , true)->getStatus();
    }
}