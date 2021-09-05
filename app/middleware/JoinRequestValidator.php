<?php


class JoinRequestValidator implements Middleware
{
    private User $user;
    private GroupOrg $group;
    /**
     * @inheritDoc
     */
    function initParameters($params)
    {}

    function init(Request $request, GroupOrg $groupOrg){
        $u = User::Query()->andWhere("email" , "=" , $request->get("email" , ""))->first();
        if (is_null($u)){
            JsonOutput::error("فرد مورد نظر پیدا نشد.")->throw();
        }

        $r = JoinRequest::Query()->andWhere("user_id" , "=" , $u->id)->andWhere("group_id" , "=" , $groupOrg->id)->first();
        if (! is_null($r)){
            JsonOutput::error("درخواست قبلا ارسال شده است")->throw();
        }

        $this->user = $u;
        $this->group = $groupOrg;
    }

    /**
     * @inheritDoc
     */
    function authorize(Request $request)
    {
        if (! $this->group->isEmployee(Auth::getUser())){
            JsonOutput::error("Access denied.")->throw();
            return false;
        }

        if($this->group->isEmployee($this->user)){
            JsonOutput::error("فرد مورد نظر در حال حاظر کارمند است.")->throw();
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    function handle(Request $request)
    {
        return Validator::check($request ,[
                "email" => ["require" , "string" , "email" , "exist:User,email"],
                "title" => ["require" , "string"],
                "group_id" => ["require" , "exist:GroupOrg,id"]
            ] ,
            [
                "email" => "ایمیل کاربر مورد نظر خود را وارد کنید",
                "title" => "سمت را وارد کنید",
                "group_id" => "گروه مورد نظر را وارد کنید"

            ]  ,
            true)->getStatus();
    }
}