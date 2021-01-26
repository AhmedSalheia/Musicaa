<?php


namespace MUSICAA\lib\traits;


trait HttpThings
{
    public function getHttpCode($link)
    {

        $handle = curl_init($link);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($handle, CURLOPT_TIMEOUT,10);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        return $httpCode;
    }

    public function sendRequest($type,$link,array $data = [])
    {
        $handle = curl_init($link);

        curl_setopt_array($handle, array(
            CURLOPT_HTTPHEADER => array(
                'Accept-Language: '.$_SESSION['lang']
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ));

        if (strtolower($type) === 'post')
        {
            curl_setopt_array($handle,array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($data)
            ));
        }

        return curl_exec($handle);
    }
}