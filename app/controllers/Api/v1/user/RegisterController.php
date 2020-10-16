<?php


namespace MUSICAA\controllers\Api\v1\user;


use MUSICAA\controllers\AbstractController;
use MUSICAA\lib\traits\Helper;

class RegisterController extends AbstractController
{
    use Helper;

    public function defaultAction()
    {

        $this->checkInput('get','firstname');

    }
}