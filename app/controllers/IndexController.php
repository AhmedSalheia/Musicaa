<?php

namespace MUSICAA\controllers;

use MUSICAA\lib\traits\Helper;

class IndexController extends AbstractController
{
    use Helper;
    public function defaultAction(){
        echo 'home';
    }
    public function tryEmailAction()
    {
        echo 'hi';
        if (isset($_GET['sub']))
        {
            $this->redirect('/');
        }
    }
}
