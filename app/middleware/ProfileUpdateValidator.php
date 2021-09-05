<?php


class ProfileUpdateValidator implements Middleware
{

    function initParameters($params){}
    function authorize(Request $request){return true;}

    /**
     * @inheritDoc
     */
    function handle(Request $request)
    {
        return Validator::check($request ,
            [
                "picture" => ['file:image/' , 'maxsize:2000000'],
                "email"   => ["string" , "email" , "unique:User,email"],
                "username"=> ["string" , "strlenbetween:3,20" , "unique:User,username"],
                "password"=> ["string" , "strlenbetween:8,50"],
                "name"    => ["string" , "strlenbetween:3,30"],
                "family"  => ["string" , "strlenbetween:3,30"],
                "address" => ["string" , "strlenbetween:3,100"]
            ] ,
            [
                "picture"   => 'تصویر ارسالی باید کمتر از 2مگابایت باشد' ,
                "email"     =>"ایمیل توسط فرد دیگری استفاده شده است از ایمیل دیگری استفاده کنید",
                "username"  =>"یوزر نیم وارد شده قبلا استفاده شده است" ,
                "name"      =>"نام باید بین ۳ تا ۳۰ کاراکتر باشد" ,
                "family"    =>"نام خانوادگی باید بین ۳ تا ۳۰ کاراکتر باشد" ,
                "address"   =>"ادرس باید بین ۳ تا ۱۰۰ کاراکتر باشد" ,
                "password"  =>"پسورد باید بین ۸ تا ۵۰ کاراکتر باشد"
                ] , true)->getStatus();

    }
}