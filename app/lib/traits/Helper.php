<?php


namespace MUSICAA\lib\traits;

trait Helper
{
    use InputFilter;
    use Encription;
    use Mailing;

    public function redirect($page){
        session_write_close();
        header('Location: '.$page);
        exit();
    }

}