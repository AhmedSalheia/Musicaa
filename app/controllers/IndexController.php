<?php

namespace MUSICAA\controllers;

use MUSICAA\lib\traits\Helper;

class IndexController extends AbstractController
{
    use Helper;
    public function defaultAction(){
        echo 'home';
    }
}
