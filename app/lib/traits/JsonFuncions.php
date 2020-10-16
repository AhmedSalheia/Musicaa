<?php


namespace MUSICAA\lib\traits;


trait JsonFuncions
{

    public function jsonRender($message,$language,$status=NULL){

        if ($status === NULL)
        {
            $status = is_array($message);
        }

        echo json_encode(['response' => $message, 'status' => $status,'Content-Language' => $language],JSON_UNESCAPED_SLASHES);
        exit();
    }

}