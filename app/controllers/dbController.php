<?php


namespace MUSICAA\controllers;


use MUSICAA\models\Data;
use MUSICAA\models\Onboarding;

class dbController extends AbstractController
{
    public function defaultAction()
    {
        $data = new Data();
        $data->createTable();
        $data->addToTable([
            ['id' => 'terms', 'data' => 'lorem posim abosem lorem posim abosem lorem posim abosem lorem posim abosem lorem posim abosem'],
            ['id' => 'privacy', 'data' => 'lorem posim abosem lorem posim abosem lorem posim abosem lorem posim abosem lorem posim abosem'],
        ]);


        $onboarding = new Onboarding();
        $onboarding->createTable();
        $onboarding->addToTable([
            ['img' => 'onboarding01.png','title' => 'Listen freely','details' => 'Musicana provides the latest world tunes and music'],
            ['img' => 'onboarding02.png','title' => 'Enjoy without internet','details' => 'Save and play music in internet off mode'],
            ['img' => 'onboarding03.png','title' => 'Follow Up Close','details' => 'Follow the artists closest to your area'],
            ['img' => 'onboarding04.png','title' => 'Big updates','details' => 'There are great features available within the app that you have not seen before.'],
        ]);
    }
}