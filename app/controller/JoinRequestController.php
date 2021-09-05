<?php


class JoinRequestController
{
    function index(){
        $req = JoinRequest::Query()->andWhere("user_id" , "=" , Auth::getUser()->id)
            ->join(User::class , "id" , "boss_id")
            ->join(GroupOrg::class , "id" , "group_id")->get();

        return JsonOutput::value($req);
    }
    function store(GroupOrg $group , Request $request){
        $req = new JoinRequest();
        $req->boss_id = Auth::getUser()->id;
        $req->user_id = User::Query()->andWhere("email" , "=" , $request->get("email" , ""))->first()->id;
        $req->group_id = $group->id;
        $req->title = $request->get("title");

        if (!$req->save()){
            return JsonOutput::error("خطا غیر منتظره");
        }

        return JsonOutput::info("در خواست شما با موفقیت انجام شد")->addValue($req);
    }
    function update(JoinRequest $join , Request $request){
        $a = $request->get("accept");
        if ($a === 0 || strtolower($a) == "false"){
            $a = 0;
        }else{
            $a = 1;
        }
        $join->accept = $a;

        if (!$join->update()){
            return JsonOutput::error("خطا غیر منتظره");
        }
        if ($a)
            return JsonOutput::info("عضویت شما با موفقیت انجام شد");
        else
            return JsonOutput::info("در خواست رد شد");
    }
}