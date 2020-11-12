<?php

$crons = [['version' => 'V1','links' => ['cron/crons']]];

foreach ($crons as $cron)
{

    $version = $cron['version'];
    $links = $cron['links'];

    foreach ($links as $link)
    {
        $class = '\MUSICAA\controllers\Api\\'.strtolower($version).'\cron\CronsController';
        $cron = new $class;
        var_dump($cron);
    }

}