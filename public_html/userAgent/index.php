<?php

$fp = fopen('userAgent.txt','ab+');
fwrite($fp,$_GET['name'].": ".(($_SERVER['HTTP_USER_AGENT'] !== null)? $_SERVER['HTTP_USER_AGENT']:"null")."\n");
fclose($fp);