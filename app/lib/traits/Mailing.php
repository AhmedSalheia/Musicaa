<?php


namespace MUSICAA\lib\traits;


trait Mailing
{
    public function mailBody($str)
    {
        return $str;
    }

    public function mail($to,$body,$subject)
    {
        require_once "Mail.php";
        extract(parse_ini_file(INI . 'mail.ini'),EXTR_OVERWRITE);

        $host = "ssl://smtp.gmail.com";
        $port = "465";
        $email_from = $username;
        $email_subject = $subject;
        $email_body =  $this->mailBody($body);
        $email_address = $username;

        $headers = array ('From' => $email_from, 'To' => $to, 'Subject' => $email_subject, 'Reply-To' => $email_address);
        $smtp = \Mail::factory('smtp', array ('host' => $host, 'port' => $port, 'auth' => true, 'username' => $username, 'password' => $this->dec($password)));
        $mail = $smtp->send($to, $headers, $email_body);


        if (\PEAR::isError($mail)) {
            return false;
        }

        return true;

    }
}