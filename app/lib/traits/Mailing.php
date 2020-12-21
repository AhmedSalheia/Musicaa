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
            $mail->Host = 'musicaa.app';
            $mail->Port       = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth   = true;

            $username = 'noreply@musicaa.app';
            $mail->Username = $username;
            $mail->Password = '6jS0U#YG^!jJ';
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
