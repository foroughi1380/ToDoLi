<?php


class Validator
{
    static function check(Request $req , array $check , array $errors_message , bool $echoout_when_erroe = false){
        $out = new JsonOutput();
        foreach ($check as $name => $items){
            foreach ($items as $item){
                $data = explode(":" , $item);
                $item_title = $data[0];
                switch ($item_title){
                    case "require":
                        if (! ($req->hasKey($name) || $req->hasFile($name))){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                    break;

                    case "int":
                        if ($req->hasKey($name) && ! is_int($req->get($name))){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                    break;

                    case "string":
                        if ($req->hasKey($name) && ! is_string($req->get($name))){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                    break;

                    case "double":
                        if ($req->hasKey($name) && ! is_double($req->get($name))){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                    break;

                    case "bool":
                        if ($req->hasKey($name) && ! (is_bool($req->get($name)) ||  strtolower($req->get($name)) === "false" || strtolower($req->get($name)) === "true" || $req->get($name) === 0 || $req->get($name) === 1 )){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                    break;

                    case "email":
                        if ($req->hasKey($name) && ! filter_var($req->get($name), FILTER_VALIDATE_EMAIL)){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                    break;
                    case "between" :
                        $values = explode("," , $data[1]);
                        if ($req->hasKey($name) && ! ($req->get($name) > $values[0] && $req->get($name) < $values[1])){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                    break;

                    case "strlen" :
                        $values = explode("," , $data[1]);
                        if ($req->hasKey($name) && ! (strlen($req->get($name)) != $values[0]) ){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                    break;

                    case "strlenbetween":
                        $values = explode("," , $data[1]);
                        if ($req->hasKey($name) && ! (strlen($req->get($name)) >= $values[0] && strlen($req->get($name)) <= $values[1]) ){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                    break;

                    case "unique" :
                        $values = explode("," , $data[1]);
                        include_once __DIR__ . "/../../app/model/{$values[0]}.php";
                        if ($req->hasKey($name) && count($values[0]::Query()->andWhere($values[1] , "=" , $req->get($name))->get()) != 0){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                    break;

                    case "exist":
                        $values = explode("," , $data[1]);
                        include_once __DIR__ . "/../../app/model/{$values[0]}.php";
                        if ($req->hasKey($name) && count($values[0]::Query()->andWhere($values[1] , "=" , $req->get($name))->get()) == 0){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                        break;

                    case "file":
                        if (! $req->hasFile($name) && $req->hasKey($name)){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }

                        if ($req->hasFile($name) && isset($data[1]) && strpos( $req->getFile($name)->type(), $data[1]) !== 0){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                        break;

                    case "maxsize":
                            if ($req->hasFile($name) && $req->getFile($name)->size() > $data[1]){
                                $out->setStatus(false);
                                $out->addErrors($errors_message[$name]);
                                echo "in max";
                            }
                        break;

                    case "minsize":
                        if ($req->hasFile($name) && $req->getFile($name)->size() < $data[1]){
                            $out->setStatus(false);
                            $out->addErrors($errors_message[$name]);
                        }
                        break;
                }
            }
        }

        if ($echoout_when_erroe && ! $out->getStatus()){
            core_setResponseCode(403);
            echo $out;
        }

        return $out;
    }
}