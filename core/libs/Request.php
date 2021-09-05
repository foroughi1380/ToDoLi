<?php


class Request
{
    public string $method;
    public string $action;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        if (! isset($_GET['a'])){
            $out = new JsonOutput();
            $out->setStatus(false);
            $out->addErrors("action not found");
            core_setResponseCode(403);
            exit($out);
        }
        $this->action = $_GET['a'];
    }


    /**
     * @param string $key
     * @return bool
     */
    function hasKey($key){
        return isset($_REQUEST[$key]);
    }

    /**
     * @param string $key
     * @return Mixed
     */
    function get($key , $def=null){
        if (! $this->hasKey($key)) return $def;
        return $_REQUEST[$key];
    }


    function hasFile($key){
        return isset($_FILES[$key]) && $_FILES[$key]['error'] === 0;
    }

    function getFile($key){
        if (! $this->hasFile($key)) return null;

        return new File($_FILES[$key]);
    }
}

class File{
    var $fileInformation;
    public function __construct($file_information)
    {
        $this->fileInformation = $file_information;
    }

    function size(){
        return $this -> fileInformation['size'];
    }

    function type(){
        return $this -> fileInformation['type'];
    }

    function save($dir="file"){
        $name = uniqid(random_int(0 , 1000) . "");
        $store_dir = STORAGE_DIR . "/$dir";
        if (! is_dir($store_dir)){
            mkdir($store_dir);
        }
        if (move_uploaded_file( $this -> fileInformation['tmp_name'] , "$store_dir/$name")){
            return "$dir/$name";
        }else{
            return null;
        }
    }
}