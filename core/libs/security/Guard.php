<?php


class Guard implements Middleware
{

    var $guard = "def";
    var $redirect = null;

    function initParameters($params)
    {
        if (isset($params[0])){
            $this->guard = $params[0];
        }
        if (isset($params[1])){
            $this->redirect = $params[1];
        }
    }

    /**
     * @inheritDoc
     */
    function authorize(Request $request)
    {
        if (Auth::isLogin($this->guard)){
            return true;
        }else{
            if ($this->redirect){
                core_redirect($this->redirect);
                exit();
            }else{
                core_setResponseCode(403);
                $out = new JsonOutput();
                $out->setStatus(false);
                $out->addErrors("Forbidden!! You most login.");
                echo $out;
            }
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    function handle(Request $request)
    {return true;}


}