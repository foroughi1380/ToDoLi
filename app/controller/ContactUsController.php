<?php


class ContactUsController
{
    function store(Request $re){
        $message = new ContactUsMessage();
        $message->name    = $re->get("name");
        $message->email   = $re->get("email");
        $message->message = $re->get("message");

        $out = new JsonOutput();
        if (! $message->save()){
            $out->addErrors("خطای ناشناخته بعدا دباره تلاش کنید");
            $out->setStatus(false);
            core_setResponseCode(500);
        }else{
            $out->addInfo("پیام شما با موفقین ثبت شد");
            core_mailTo("foroghi.1380@gmail.com" , $message->email , "پیام از طریق تماس با ما در صفحه اصلی" , $message);
        }

        return $out;
    }
}