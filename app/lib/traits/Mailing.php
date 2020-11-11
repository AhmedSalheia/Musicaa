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
        require_once('Mail.php');
        require_once('Mail/mime.php');

        $message = new Mail_mime();

        $message->setTXTBody("This is the text version.");

        $message->setHTMLBody("This is the <strong>HTML</strong> version.");

        $recipients = 'person@example.net';

        $headers['From'] = 'somesender@example.com';
        $headers['To'] = 'person@example.net';
        $headers['Subject'] = 'Sending test message using Pear';

        $mail =& Mail::factory('mail');

        $result = $mail->send($recipients, $message->headers($headers), $message->get());

        var_dump($result);

    }
}