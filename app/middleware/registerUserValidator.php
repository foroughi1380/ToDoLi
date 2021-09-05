<?php


class registerUserValidator implements Middleware
{
    function initParameters($params){}

    /**
     * @inheritDoc
     */
    function authorize(Request $request)
    {
        return Auth::checkNoLogin();
    }

    /**
     * @inheritDoc
     */
    function handle(Request $request)
    {
        return Validator::check($request , ["email"   => ["require" , "string" , "email"  , "unique:User,email"],
                                            "username"=> ["require" , "string" , "strlenbetween:3,20" , "unique:User,username"],
                                            "password"=> ["require" , "string" , "strlenbetween:8,50"],
                                            "name"    =>["require" , "string" , "strlenbetween:3,30"],
                                            "family"    =>["require" , "string" , "strlenbetween:3,30"],
                                            "address" =>["string" , "strlenbetween:3,100"]],

                                            ["email"=>"ایمیل خود را صحیح و منجصر به فرد وارد کنید",
                                             "username"=>"یوزر نیم وارد شده قبلا استفاده شده است" ,
                                             "name"=>"نام باید بین ۳ تا ۳۰ کاراکتر باشد" ,
                                             "family"=>"نام خانوادگی باید بین ۳ تا ۳۰ کاراکتر باشد" ,
                                             "address"=>"ادرس باید بین ۳ تا ۱۰۰ کاراکتر باشد" ,
                                             "password"=>"پسورد باید بین ۸ تا ۵۰ کاراکتر باشد"] , true)->getStatus();
    }
}