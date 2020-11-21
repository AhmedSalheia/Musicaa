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
}