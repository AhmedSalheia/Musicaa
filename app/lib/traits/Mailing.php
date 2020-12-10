<?php


namespace MUSICAA\lib\traits;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

trait Mailing
{
    public function mail($to,$body,$subject)
    {
        extract(parse_ini_file(INI.'mail.ini'));
        try
        {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port       = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth   = true;

            $username = $this->dec($username);
            $mail->Username = $username;
            $mail->Password = $this->dec($password);
            $mail->SetFrom($username, 'Musicaa App');
            $mail->addAddress($to);

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
