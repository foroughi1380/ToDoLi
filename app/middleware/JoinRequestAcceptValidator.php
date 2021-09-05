<?php


class JoinRequestAcceptValidator implements Middleware
{
    private JoinRequest  $join;
    /**
     * @inheritDoc
     */
    function initParameters($params)
    {}

    function init(JoinRequest $join){
        $this->join = $join;
    }

    /**
     * @inheritDoc
     */
    function authorize(Request $request)
    {
        if ($this->join->user_id !== Auth::getUser()->id){
            JsonOutput::error("Access denied")->throw();
            return false;
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    function handle(Request $request)
    {
        return Validator::check($request , ["accept"=>["require" , "bool"]] , ["accept"=>"تاییدیه دریافت نشد"] , true)->getStatus();
    }
}