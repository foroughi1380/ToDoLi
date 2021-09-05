<?php


class ToDoController
{
    function index(GroupOrg $groupOrg)
    {
        if (!$groupOrg->isEmployee(Auth::getUser())) {
            return JsonOutput::error("شما کارمند این گروه یاشرکت نیستید.");
        }

        $todo_list = ToDo::Query()->andWhere("user_id", "=", Auth::getUser()->id)->andWhere("group_id", "=", $groupOrg->id)->get();

        return JsonOutput::value($todo_list);
    }

    function employeeIndex(GroupOrg $groupOrg, User $user)
    {
        if (!$user->isYourBoss($groupOrg , Auth::getUser())) {
            return JsonOutput::error("شما قادر به دیدن وظایف شخص مورد نظر نیستید");
        }
        $todo = ToDo::Query()->andWhere("user_id", "=", $user->id)
                            ->andWhere("group_id", "=", $groupOrg->id)
                            ->setInsertDeleteWhere(false)
                            ->customWhere(" and (deleted = 0 or answered = 1)")
                            ->get();

        return JsonOutput::value($todo);

    }

    function store(GroupOrg $groupOrg, User $user, Request $request)
    {
        $todo = new ToDo();

        $todo->title = $request->get("title");
        $todo->description = $request->get("description");
        $todo->owner_id = Auth::getUser()->id;
        $todo->user_id = $user->id;
        $todo->group_id = $groupOrg->id;
        $todo->answered = (int)false;

        if ($request->hasFile("picture")) {
            $todo->picture = $request->getFile("picture")->save('images');
        }

        if ($request->hasFile("file")) {
            $todo->file = $request->getFile("file")->save();
        }

        if (!$todo->save()) {
            return JsonOutput::error("خطای ناشناخته");
        }

        return JsonOutput::info("درخواست شما با موفقت ثبت شد")->addValue($todo);
    }

    function update(ToDo $todo, Request $request)
    {

        $todo->title = $request->get("title", $todo->title);
        $todo->description = $request->get("description", $todo->description);

        if ($request->hasFile("picture")) {
            $todo->picture = $request->getFile("picture")->save('images');
        }

        if ($request->hasFile("file")) {
            $todo->file = $request->getFile("file")->save();
        }

        if (!$todo->update()) {
            return JsonOutput::error("خطای ناشناخته");
        }

        return JsonOutput::info("درخواست شما با موفقت ثبت شد")->addValue($todo);
    }

    function delete(ToDo $todo, Request $request)
    {
        if (!$todo->mine()) JsonOutput::error("شما قادر به حذف نیستید")->throw();

        if (!$todo->delete()) {
            return JsonOutput::error("خطای ناشناخته");
        }

        return JsonOutput::info("کار حذف شد");

    }

    function answer(ToDo $todo, Request $request)
    {

        $todo->answered = (int)true;

        if ($request->hasFile("answerFile")) {
            $todo->answerFile = $request->getFile("answerFile")->save("af");
        }

        if (!$todo->update()) {
            return JsonOutput::error("خطای ناشناخته");
        }

        $todo->delete();

        return JsonOutput::info("کار پایان یافت");
    }
}