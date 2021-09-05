<?php


class GroupController
{
    function index(){
        return JsonOutput::value(GroupOrg::Query()->rawSelect("SELECT grouporg.* FROM `grouporg` inner join `employs` on `grouporg`.id = `employs`.group_id ")
            ->customWhere("and employs.group_id = grouporg.id")->andWhere("employs.user_id"  , "=" , Auth::getUser()->id)->get());
    }

    function store(Request $request){
        $group = new GroupOrg();
        $group->name = $request->get("name");
        $group->description = $request->get("description");
        $group->user_id = Auth::getUser()->id;

        if (! $group->save()) {
            core_setResponseCode(500);
            return JsonOutput::error("خطایی در سرور رخ داد لطفا بعدا تلاش کنید");
        }

        return JsonOutput::info("ساخت گروه یا شرکت با موفقیت انجام شد")->addValue($group);
    }

    function update(GroupOrg $group , Request $request){
        $group->name = $request->get("name" , $group->name);
        $group->description = $request->get("description" , $group->description);

        if (! $group->update()) {
            core_setResponseCode(500);
            return JsonOutput::error("خطایی در سرور رخ داد لطفا بعدا تلاش کنید");
        }
        return JsonOutput::value($group)->addInfo("ویرایش گروه یا شرکت با موفقیت انجام شد");
    }

    function delete(GroupOrg $group){
        if (!$group->delete()) {
            core_setResponseCode(500);
            return JsonOutput::error("خطایی در سرور رخ داده است لطفا بعدا تلاش کنید");
        }
        return JsonOutput::info("حذف گروه یا شرکت با موفقیت انجام شد");
    }
}