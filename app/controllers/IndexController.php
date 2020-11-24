<?php

namespace MUSICAA\controllers;

use MUSICAA\lib\traits\Helper;

class IndexController extends AbstractController
{
    use Helper;
    public function defaultAction(){

    }
    public function tryEmailAction()
    {
        var_dump($this->mail('ahmedsalheia.as@gmail.com','Hi There','HIIIIIIIIIIIIIIIIIIIIIIIIIII!!!'));
    }
}