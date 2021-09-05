<?php


class EmployeeController
{
    function index(GroupOrg $groupOrg){
        if ( ! $groupOrg->isEmployee(Auth::getUser())){
            return JsonOutput::error("Access Denied");
        }

        return JsonOutput::value($groupOrg->getChart());
    }
}