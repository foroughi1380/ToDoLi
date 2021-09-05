<?php
class JsonOutput{
    private array $values;
    private array $errors;
    private array $infos;
    private array $warnings;
    private bool $status = true;

    function __construct()
    {
        $this->values = array();
        $this->errors = array();
        $this->infos = array();
        $this->warnings = array();
    }

    public function addValueArr(array $arr){
        foreach ($arr as $a){
            $this->addValue($a);
        }
        return $this;
    }

    public function addValue($v){
        if (is_array($v)){
            $this->values[] = $this->replaceModelInArray($v);
        }else if ($v instanceof Model){
            $this->values[] = $this->replaceModelInModel($v);
        }else{
            $this->values[] = $v;
        }
        return $this;
    }

    public function addErrorsArr(array $arr){
        foreach ($arr as $a){
            $this->addErrors($a);
        }
        return $this;
    }

    public function addErrors($e){
        $this->errors[] = $e;
        return $this;
    }

    public function addInfoArr(array $arr){
        foreach ($arr as $a){
            $this->addInfo($a);
        }
        return $this;
    }

    public function addInfo($i){
        $this->infos[] = $i;
        return $this;
    }

    public function addWarningArr(array $arr){
        foreach ($arr as $a){
            $this->addWarning($a);
        }
        return $this;
    }

    public function addWarning($w){
        $this->warnings[] = $w;
        return $this;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values)
    {
        $this->values = $values;
        return $this;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status)
    {
        $this->status = $status;
        return $this;
    }

    function __toString()
    {
        header('Content-type:application/json;charset=utf-8');
        return $this->toJson();
    }

    /**
     * @return String
     */
    function toJson(){
        return json(array(
            'info'     =>   $this->infos,
            'errors'   =>   $this->errors,
            'warnings' => $this->warnings,
            'values'   =>   $this->values
        ) , $this->status);

    }

    function replaceModelInArray($arr){
        foreach ($arr as $key => $value){
            if (is_array($value)){
                $arr[$key] = $this->replaceModelInArray($value);
            }else if($value instanceof Model){
                $arr[$key] = $this->replaceModelInModel($value);
            }
        }
        return $arr;
    }

    /**
     * @param Model $model
     */
    function replaceModelInModel($model){
        $arr = $model->toArray();
        unset ($arr['password']);
        unset ($arr['username']);
        foreach ($arr as $key=>$value) {
            if (is_array($value)){
                $arr[$key] = $this->replaceModelInArray($value);
            }else if($value instanceof Model){
                $arr[$key] = $this->replaceModelInModel($value);
            }
        }

        return $arr;
    }


    function throw($responseCode = 403){
        core_setResponseCode($responseCode);
        exit($this);
    }
    static function value($value , $status = true){
        $out = new JsonOutput();
        $out->addValue($value);
        $out->status = $status;
        return $out;
    }

    static function error($err , $status = false){
        $out = new JsonOutput();
        $out->addErrors($err);
        $out->status = $status;
        return $out;
    }
    static function info($info , $status = true){
        $out = new JsonOutput();
        $out->addInfo($info);
        $out->status = $status;
        return $out;
    }


}
