<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(30 * 24 * 60 * 60, '/');
    session_start();
}

class Auth
{
    /**
     * @param string $guard
     * @return bool
     */
    static function isLogin($guard="def"){
        return isset($_SESSION[$guard . "loginedmodel_Model"]);
    }

    /**
     * @param Model $model
     * @return bool
     */
    static  function login($model , $guard = "def"){
        $_SESSION[$guard . "loginedmodel_Model"] = $model;
        return self::isLogin($guard);
    }

    /**
     * @param string $guard
     * @return bool
     */
    static function logout($guard = "def"){
        unset($_SESSION[$guard . "loginedmodel_Model"]);
        return self::isLogin($guard);
    }

    /**
     * @return core_storageSession
     */
    static function getStorage(){
        return new core_storageSession();
    }

    /**
     * @param string $guard
     * @return Model|null
     */
    static function getUser($guard = "def"){
        if (self::isLogin($guard)){
            $su = $_SESSION[$guard . "loginedmodel_Model"];
            return $su;
        }
        return null;
    }
    static function checkNoLogin($guard = "def"){
        if (self::isLogin($guard)){
            $out = new JsonOutput();
            $out->setStatus(false);
            $out->addErrors("Forbidden!! You most logout.");
            core_setResponseCode(403);
            echo $out;
            return false;
        }
        return true;
    }

    static function checkLogin($guard = "def"){
        if (self::isLogin($guard)){
            $out = new JsonOutput();
            $out->setStatus(false);
            $out->addErrors("Forbidden!! You most login.");
            core_setResponseCode(403);
            echo $out;
            return false;
        }
        return true;
    }
}

class core_storageSession{

    function hasKey($key){
        return isset($_SESSION[$key]);
    }

    function get($key , $def=null){
        if ($this->hasKey($key)){
            return $_SESSION[$key];
        }
        return $def;
    }
}