<?php


class loginUserValidator implements Middleware
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
       return Validator::check($request , ["username"=> ["require" , "string"],
                                            "password"=> ["require" , "string"]],

                                            ["username"=>"یوزر نیم را وارد کنید" ,
                                             "password"=>"پسورد را وارد کنید"] , true)->getStatus();
    }
}