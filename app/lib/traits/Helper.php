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

    public function randText($num)
    {
        return str_split(str_shuffle('absdefghijklmnopqrstuvwxyz1234567890'),$num)[0];
    }
}