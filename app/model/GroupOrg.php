<?php

class GroupOrg extends Model
{
    public static string $requestIdName = "group_id";

    var $name;
    var $user_id;
    var $description;

    function canEdit(){
        return Auth::getUser()->id === $this->user_id;
    }

    function isEmployee(User $user){
        return (bool) count(Employs::Query()->andWhere("user_id" , "=" , $user->id)->get());
    }


    function getChart(){
        /** @var Employs $employee */
        $employee = Employs::Query()->andWhere("user_id" , "=" , Auth::getUser()->id)->andWhere("group_id" , "=" , $this->id)->first();
        $boss = $employee->getBoss();

        if (is_null($boss)){
            $self = Auth::getUser();
            $self->password = "";
            $self->username = "";
            return $this->createUserChart($self);
        }else{
            $boss->password = "";
            $boss->username = "";
            return $this->createUserChart($boss);
        }
    }

    /**
     * @param $user
     * @return User
     */
    private function createUserChart(User $user){
        $user_employs = Employs::Query()->andWhere("boss_id" ,'=' , $user->id)->andWhere("group_id" , "=" , $this->id)->get();

        if (empty($user_employs)){
            return $user;
        }else{
            $users = [];
            foreach ($user_employs as $employ){
                $u = $employ->getUser();
                $u->password = "";
                $u->username = "";

                $users[] = $this->createUserChart($u);
            }
            $user->employs = $users;
            return $user;
        }
    }
}