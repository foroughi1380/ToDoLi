<?php


class Employs extends Model
{
    var $group_id;
    var $user_id;
    var $boss_id;
    var $title;

    /**
     * @return User|null
     */
    function getUser(){
        return User::Query()->getById($this->user_id);
    }

    /**
     * @return User|null
     */
    function getBoss(){
        return User::Query()->getById($this->boss_id);
    }

    /**
     * @param GroupOrg $group
     * @param User $user
     * @return Employs|null
     */
    static function getByUserAndGroup(GroupOrg $group , User $user){
        return Employs::Query()->andWhere("group_id" , "=" , $group->id)->andWhere("user_id" , "=" , $user->id)->first();
    }
}