<?php


namespace MUSICAA\lib\traits;

trait Helper
{
    use InputFilter;

    public function redirect($page){
        session_write_close();
        header('Location: '.$page);
        exit();
    }

    public function jsonRender($message,$language,$status=NULL){

        if ($status === NULL)
        {
            $status = is_array($message);
        }

        echo json_encode(['response' => $message, 'status' => $status,'Content-Language' => $language]);
        exit();
    }
}