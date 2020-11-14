<?php


namespace MUSICAA\controllers;


use MUSICAA\models\DefaultSettings;
use MUSICAA\models\Devices;
use MUSICAA\models\Languages;
use MUSICAA\models\Login;
use MUSICAA\models\OS;
use MUSICAA\models\Settings;
use MUSICAA\models\Status;
use MUSICAA\models\Theme;
use MUSICAA\models\TokenMod;
use MUSICAA\models\Tracker;
use MUSICAA\models\Verification;
use MUSICAA\models\Data;
use MUSICAA\models\GenderLabels;
use MUSICAA\models\Genders;
use MUSICAA\models\Onboarding;
use MUSICAA\models\User;
use MUSICAA\models\youtube\Channels;
use MUSICAA\models\youtube\Playlists;
use MUSICAA\models\youtube\Video;

class DbController extends AbstractController
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
            ['img' => 'onboarding01','title' => 'Listen freely','details' => 'Musicana provides the latest world tunes and music'],
            ['img' => 'onboarding02','title' => 'Enjoy without internet','details' => 'Save and play music in internet off mode'],
            ['img' => 'onboarding03','title' => 'Follow Up Close','details' => 'Follow the artists closest to your area'],
            ['img' => 'onboarding04','title' => 'Big updates','details' => 'There are great features available within the app that you have not seen before.'],
        ]);

        $genderLabels = new GenderLabels();
        $genderLabels->createTable();
        $genderLabels->addToTable([
            ['id' => 'en', 'male' => 'Male', 'female' => 'Female', 'ratherNotToSay' => 'Rather Not To Say','custom' => 'Custom'],
            ['id' => 'ar', 'male' => 'ذكر', 'female' => 'انثى', 'ratherNotToSay' => 'أفضل عدم الاجابة','custom' => 'مخصص']
        ]);

        $genders = new Genders();
        $genders->createTable();
        $genders->addToTable([
            ['gender' => 'male'],
            ['gender' => 'female'],
            ['gender' => 'ratherNotToSay'],
            ['gender' => 'custom']
        ]);

        $user = new User();
        $user->createTable();

        $verification = new Verification();
        $verification->createTable();

        $os = new OS();
        $os->createTable();
        $os->addToTable([
            ['OS' => 'Android']
        ]);

        $devices = new Devices();
        $devices->createTable();

        $login = new Login();
        $login->createTable();

        $tokenMod = new TokenMod();
        $tokenMod->createTable();

        $status = new Status();
        $status->createTable();

        $tracker = new Tracker();
        $tracker->createTable();

        $theme = new Theme();
        $theme->createTable();
        $theme->addToTable([['name'=>'Dark'],['name'=>'Moon']]);

        $languages = new Languages();
        $languages->createTable();
        $languages->addToTable([['name' => 'en']]);

        $defaultSet = new DefaultSettings();
        $defaultSet->createTable();
        $defaultSet->os = 1;
        $defaultSet->theme = 1;
        $defaultSet->language = 1;
        $defaultSet->additional_screen = 1;
        $defaultSet->auto_update = 1;
        $defaultSet->permissions = 7;
        $defaultSet->save();

        $settings = new Settings();
        $settings->createTable();

        $channels = new Channels();
        $channels->createTable();

        $playlists = new Playlists();
        $playlists->createTable();

        $video = new Video();
        $video->createTable();
    }
}