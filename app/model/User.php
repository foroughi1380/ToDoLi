<?php

class User extends Model
{
    public static string $requestIdName = "user_id";

    var $name;
    var $family;
    var $username;
    var $password;
    var $address;
    var $email;
    var $picture;

    function isYourBoss(GroupOrg $group , User $user){
       $employee = Employs::getByUserAndGroup($group , $this);

       while ($employee != null){
           $boss = $employee->getBoss();

           if ($boss == null) break;

           if ($boss->id == $user->id) return true;

           $employee = Employs::getByUserAndGroup($group , $boss);
       }

       return false;
    }
}