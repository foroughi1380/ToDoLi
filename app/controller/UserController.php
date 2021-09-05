<?php

class UserController
{
    function index(){
//        $out = new JsonOutput();
        $user = User::byId(Auth::getUser()->id);
        $user->password = "";
        //$out->addValue($user);
//        return $out;
        return JsonOutput::value($user);

    }

    function register(Request $r){
        $user = new User();
        $user->name = $r->get("name");
        $user->family = $r->get("family");
        $user->username = $r->get("username");
        $user->password = md5($r->get("password"));
        $user->email = $r->get("email");
        $user->address = $r->get("address");
        $user->picture = null;

        if (! $user->save()) {
            return JsonOutput::error("خطایی در ثبت نام رخ داده است لطفا بعدا تلاش کنید.");
        }

        $user->password = "";
        if ($r->get("login" , false)){
            Auth::login($user);
        }

        return JsonOutput::info("ثبت نام شمابا موفقیت انجام شد")->addValue($user);
    }

    function login(Request $r){
        $out = new JsonOutput();
        $query = User::Query()->andWhere("username" , "=" ,$r->get("username"))->get();
        if (count($query) == 0){
            $out->setStatus(false);
            $out->addErrors("نام کاربری یا رمز عبور اشتباه است.");
        }else if ($query[0]->password  !== md5($r->get("password"))){
            $out->setStatus(false);
            $out->addErrors("نام کاربری یا رمز عبور اشتباه است.");
        }else{
            $out->addInfo("خوش امدید");
            $query[0]->password = "";
            $out->addValue($query[0]);
            Auth::login($query[0]);
        }

        return $out;
    }

    function logout(){
        $out = new JsonOutput();
        if (Auth::isLogin()){
            Auth::logout();
            $out->addInfo("شما با موفقیت خارج شدید.");
        }else{
            $out->addErrors("لطفا اول وارد شوید.");
            $out->setStatus(false);
        }

        return $out;
    }

    function update(Request $request){
        /** @var User $user */
        $user = User::Query()->getById( Auth::getUser()->id);
        $user->name      = $request->get("name" , $user->name);
        $user->family      = $request->get("family" , $user->family);
        $user->username = $request->get("username" , $user->username);
        $user->password = $request->get("password" , $user->password);
        $user->email    = $request->get("email" , $user->email);
        $user->address  = $request->get("address" , $user->address);

        if ($request->hasFile("picture")){
            $user->picture = $request->getFile("picture")->save("images");
        }

        if (! $user->update()) {
            return JsonOutput::error("خطای ناشناخته");
        }

        return JsonOutput::value($user)->addInfo("ویرایش با موفقیت انجام شد");
    }
}