<?php


namespace MUSICAA\lib\traits;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

trait Mailing
{
    public function mail($to,$body,$subject)
    {
        try
        {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'musicaa.app';
            $mail->Port       = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth   = true;

//            $mail->SMTPDebug = SMTP::DEBUG_SERVER;

            $username = 'noreply@musicaa.app';
            $mail->Username = $username;
            $mail->Password = 'd-nwc.vvmPuC';
            $mail->SetFrom($username, 'Musicaa App');
            $mail->addAddress($to);

            $mail->IsHTML(true);

            $mail->Subject = $subject;
            $mail->Body    = $body;

            if($mail->send()) {
                return true;
            }

            return false;

        }catch(Exception $ex)
        {
            return false;
        }
    }
}
