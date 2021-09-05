<?php


class ContactUsValidator implements Middleware
{

    /**
     * @inheritDoc
     */
    function initParameters($params){}

    /**
     * @inheritDoc
     */
    function authorize(Request $request){return true;}

    /**
     * @inheritDoc
     */
    function handle(Request $request)
    {
        return Validator::check($request , ["name"=> ["require" , "string" , "strlenbetween:3,30"],
                                            "message"=> ["require" , "string" ,  "strlenbetween:10,150"],
                                            "email"=> ["require" , "email"]],

                                            ["name"=>"نام باید بین ۳ تا ۳۰ کاراکتر باشد" ,
                                             "message"=>"پیام باید بین ۱۰ تا ۱۵۰ کاراکتر باشد",
                                             "email"=>"ایمیل خود را صحیح وارد کنید"] , true)->getStatus();
    }
}