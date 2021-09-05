<?php


class Model
{
    public static string $requestIdName = "id";
    var $id;
    var $deleted;
    var $createdat;
    var $updatedat;
    protected array $mainIgnoreVariables = ['mainIgnoreVariables' , 'IgnoreVariables' , 'id' , 'deleted' , 'requestIdName' , 'createdat' , 'updatedat'];
    protected array $IgnoreVariables = [];

    function __construct($values = [])
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @return bool
     */
    function save(){
        $database = core_getDatabase();
        $result = $database->executeArgs($this->createInsertSql() , array_merge($this->getValues() , [date("Y-m-d H:i:s") , date("Y-m-d H:i:s")]) , true);

        if ($result->status){
            $this->id = $result->id_resualt;
        }

        $database->commit();

        unset($database);


        if ($ob = core_get_model_observe(get_class($this))){
            $ob->inserted($this);
        }
        return $result->status;
    }

    /**
     * @return bool
     */
    function update(){
        $database = core_getDatabase();
        $result = $database->executeArgs($this->createUpdateSql() , $this->getValues());

        $database->commit();

        unset($database);

        if ($ob = core_get_model_observe(get_class($this))){
            $ob->updated($this);
        }
        return $result->status;

    }

    function saveOrUpdate(){
        if (is_null($this->id) || ! is_integer($this->id) || $this->id <= 0){
            $this->save();
        }else{
            $this->update();
        }
    }

    function delete(){
        $database = core_getDatabase();
        $result = $database->executeArgs($this->createDeleteSql() , []);

        $database->commit();

        unset($database);

        if ($ob = core_get_model_observe(get_class($this))){
            $ob->deleted($this);
        }


        return $result->status;

    }


    function toArray(){
        $ret = [];
        $ret['id'] = $this->id;

        foreach (get_object_vars($this) as $key => $value){
            if ($key != 'updatedat' && (in_array($key , $this->mainIgnoreVariables) || in_array($key , $this->IgnoreVariables))) continue;
            $ret[$key] = $value;
        }
        $ret['deleted'] = $this->deleted;
        return $ret;
    }

    /**
     * @param Model $model
     * @param null | string $col_one
     * @param string $col_two
     * @return bool
     */
    function with($model , $col_one=null , $col_two="id"){
        if (is_null($col_one)){
            $col_one = get_class($model) . "_id";
        }

        $values = $model::Query()->andWhere($col_one  , "=" ,  $this->$col_two)->get();
        $this->$model = $values;
        return (bool) count($values);
    }

    // Utility Functions
    private function getColumns(){
        $ret = [];
        foreach (get_object_vars($this) as $key => $value){
            if (in_array($key , $this->mainIgnoreVariables) || in_array($key , $this->IgnoreVariables)) continue;
            $ret[] = $key;
        }

        return $ret;
    }
    private function getValues(){
        $ret = array();
        foreach (get_object_vars($this) as $key=>$value){
            if (in_array($key , $this->mainIgnoreVariables) || in_array($key , $this->IgnoreVariables)) continue;
            $ret[] = $value;
        }

        return $ret;
    }
    private function getTableName(){
        return strtolower(get_class($this));
    }

    private function createInsertSql(){
        $cols = $this->getColumns();

        $ret = "INSERT INTO " . $this->getTableName() . "(";

        $i = 0;
        foreach ($cols as $col){
            if ($i++ == 0) {
                $ret .= $col;
            }else{
                $ret .= " , $col";
            }
        }
        $ret .= " , createdat , updatedat";
        $ret .= ") VALUES (";

        $i = 0;
        foreach ($cols as $col){
            if ($i++ == 0) {
                $ret .= " ?";
            }else{
                $ret .= " , ?";
            }
        }
        $ret .= " , ? , ?";
        $ret .= " )";

        return $ret;
    }

    private function createUpdateSql(){
        $cols = $this->getColumns();

        $ret = "UPDATE " . $this->getTableName() . " SET ";

        $i = 0;
        foreach ($cols as $col){
            if ($i++ == 0) {
                $ret .= "$col = ?";
            }else{
                $ret .= " , $col = ?";
            }
        }
        $ret .= " , updatedat = now() ";
        $ret .= " WHERE id = $this->id";

        return $ret;
    }
    private function createDeleteSql(){
        return "UPDATE " . $this->getTableName() . " SET deleted = True , updatedat = now() WHERE id = " . $this->id;
    }

    function fillValues($values){
        foreach ($values as $key => $value){
            $this->$key = $value;
        }
    }

    public static  function Query(){
        return new Query(get_called_class());
    }

    public static function byId($id){
        return self::Query()->getById($id);
    }

}
