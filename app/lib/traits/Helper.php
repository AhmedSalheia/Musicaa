<?php


namespace MUSICAA\lib\traits;

use Firebase\JWT\JWT;
use MUSICAA\models\TokenMod;

trait Helper
{
    use InputFilter;
    use Encription;
    use Mailing;
    use Auth;

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