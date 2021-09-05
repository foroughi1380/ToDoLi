<?php


class Query
{
    var $selectStatement = "";
    var $wherePart = " 1 ";
    var $whereValue = [];
    var $table_name;
    var $model_name;
    var $showDeleted = 0;
    var $leftJoinPart = [];
    var $JoinPart = [];
    var $insertTableName = true;
    var $insertDeleteWhere = true;


    function __construct($name)
    {
        $this->model_name = $name;
        $this->table_name = strtolower($name);
        $this->selectStatement = "SELECT * FROM `{$this->table_name}`";

    }

    /**
     * @param int $id
     * @return null|Model
     */
    function getById($id){
        $this->andWhere("id" , "=" , $id);
        $res = $this->get();
        if ( count($res) > 0){
            return new $this->table_name($res[0]);
        }

        return null;
    }

    function get(){
        $database = core_getDatabase();
        $result = $database->executeArgs($this->getSql() , $this->getArg());

        if ($result->status === false){
            return [];
        }

        if (is_array($result->resualt)) {
            $arr = [];
            foreach ($result->resualt as $item) {
                $model = new $this->model_name();
                $model->fillValues($item);
                $arr[] = $model;
            }

            foreach ($this->leftJoinPart as $left){
                $i = 0;
                foreach ($arr as $index=>$item){
                    if (! $item->with($left[0] , $left[1] , $left[2])){
                        unset($arr[$i]);
                    }
                    $i++;
                }
            }


            foreach ($this->JoinPart as $join){
                foreach ($arr as $index=>$item){
                    $item->with($join[0] , $join[1] , $join[2]);
                }
            }


            return $arr;
        }
        else
            return[];
    }

    /**
     * @return Model|null
     */
    function first(){
        $r = $this->get();
        if (isset($r[0])){
            return $r[0];
        }else{
            return null;
        }
    }


    private function where($s , $k , $o , $v){
        $this->wherePart .= " $s $k $o ? ";
        $this->whereValue[] = $v;
    }

    function andWhere( $k , $o , $v){
            $this->where("and" , $k , $o , $v);
            return $this;
    }


    function orWhere( $k , $o , $v){
        $this->where("or" , $k , $o , $v);
        return $this;
    }

    function customWhere($s){
        $this->wherePart .= $s;
        return $this;
    }

    /**
     * @param Model $model
     * @param string $c_one
     * @param string $c_two
     */
    function join($model , $c_one , $c_two="id"){
        $this->JoinPart[] = [$model , $c_one , $c_two];
        return $this;
    }
    /**
     * @param Model $model
     * @param string $c_one
     * @param string $c_two
     */
    function leftJoin($model , $c_one , $c_two){
        $this->leftJoinPart[] = [$model , $c_one , $c_two];
        return $this;
    }

    function rawSelect($select_statement){
        $this->selectStatement = $select_statement;
        return $this;
    }

    // Utility Functions
    function getSql(){
        return $this->selectStatement . " WHERE " . $this->wherePart . ($this->insertDeleteWhere ? " and `$this->table_name`.deleted = " . $this->showDeleted : "");
    }

    function getArg(){
        $args_arrays = [$this->whereValue];
        $ret = [];

        foreach ($args_arrays as $args_array){
            foreach ($args_array as $args){
                $ret[] = $args;
            }
        }

        return $ret;
    }

    /**
     * @param bool $b
     */
    function setDeletedItem($b){
        $this->showDeleted= (int) $b;
        return $this;
    }

    /**
     * @param bool $insertTableName
     */
    public function setInsertTableName(bool $insertTableName)
    {
        $this->insertTableName = $insertTableName;
        return $this;
    }

    /**
     * @param bool $insertDeleteWhere
     */
    public function setInsertDeleteWhere(bool $insertDeleteWhere)
    {
        $this->insertDeleteWhere = $insertDeleteWhere;
        return $this;
    }

}