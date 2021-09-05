<?php
include_once __DIR__ . "/../../../config/database/database.php";
class DataBase
{
    private mysqli $ms;
    var $sq_error = false;

    function __construct($createDB = false)
    {
        if ($createDB){
            $mysqli = new mysqli(DB_HOST , DB_USER_NAME , DB_PASSWORD , null , DB_PORT);

            $mysqli->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci");

            $mysqli->close();
        }

        $this->ms = new mysqli(DB_HOST , DB_USER_NAME , DB_PASSWORD , DB_NAME , DB_PORT);
        $this->ms->begin_transaction();
    }

    function execute($sql , ...$args){
        return $this->executeArgs($sql , $args);
    }

    function executeArgs($sql , array $args , $returnedId = false){
        $ret = new DataBaseExecuteResponse();
        $stmt = $this->ms->prepare($sql);

        if (! $stmt){
            $ret->status = false;
            $ret->error = $this->ms->error;
            return $ret;
        }

        if (! empty($args)) {
            $types = "";
            foreach ($args as $arg) {
                $type = gettype($arg)[0];
                if ($type !== "i" && $type !== "d" && $type !== "s" && $type !== "b" )
                    $type = "s";

                $types .= $type;
            }

            $stmt->bind_param($types , ...$args);

//            $r = new ReflectionMethod($stmt, 'bind_param');
//            $r->invokeArgs($stmt, array_merge([$types], $args));
        }

        if ( !$stmt->execute() && $stmt->errno){
            $this->sq_error = true;
            $ret->status = false;
            $ret->error = $this->ms->error;
            return $ret;
        }

        $ret->status = true;



        $result = $stmt->get_result();
        if (is_bool($result)){
            $ret->resualt = $result;
        }else {
            $ret->resualt = [];
            while ($data = $result->fetch_assoc()) {
                $ret->resualt[] = $data;
            }
        }




        if ($returnedId){
            $ret->id_resualt = $stmt->insert_id;
        }

        return $ret;

    }

    function commit(){
        if ($this->sq_error){
            $this->ms->rollback();
        }else{
            $this->ms->commit();
        }
    }

    function __destruct()
    {
        $this->ms->close();
    }

}

class DataBaseExecuteResponse{
    var $status;
    var $error;
    var $resualt;
    var $id_resualt;
}