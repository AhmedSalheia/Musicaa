<?php


namespace MUSICAA\lib\traits;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

trait Mailing
{
    public function mail($to,$body,$subject)
    {
        try
        {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port       = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth   = true;

            $mail->Username = 'ahmedsalheia.as@gmail.com';
            $mail->Password = 'vnowjjdiirwvxpsv';
            $mail->SetFrom('ahmedsalheia.as@outlook.com', 'Musicaa App');
            $mail->addAddress($to);

//            $mail->SMTPDebug  = 1;
//$mail->Debugoutput = function($str, $level) {echo "debug level $level; message: $str";}; //$mail->Debugoutput = 'echo';

            $mail->IsHTML(true);

            $mail->Subject = $subject;
            $mail->Body    = $body;

            if($mail->send()) {
                return true;
            }

        }catch(Exception $ex)
        {
            return false;
        }
        return false;
    }
}