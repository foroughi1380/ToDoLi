<?php
function core_isValidMethod($req){
    global $core_actions;
    $action = $req->action;
    return $req->method === (isset($core_actions[$action]) ? $core_actions[$action][0] : false);
}

function core_checkValidMethod($req){
    if (! core_isValidMethod($req)){
        $out = new JsonOutput();
        $out->addErrors('Request method invalid');
        $out->setStatus(false);
        core_setResponseCode(405);
        exit($out);
    }
}

function json($value , $status = false){
    return json_encode(['status'=>$status , 'payload'=>$value] , JSON_UNESCAPED_UNICODE);
}

function core_setResponseCode($code){
    http_response_code($code);
}

function core_redirect($url){
    header("Location: $url");
}

function core_includeAllFileInDirectory($directoryPath){
    $directoryPath = __DIR__ . "/../../$directoryPath";

    foreach (new DirectoryIterator($directoryPath) as $file){
        if ($file->isFile()){
            include_once  $file->getPathname();
        }
    }
}

function core_mailTo($to , $from , $subject , $message , $ishtml = false){
    $header = "FROM:$from; charset=utf-8;";
    if ($ishtml){
        $header .= " Content-type: text/html";
    }
    mail($to , $subject , $message , $header);
}