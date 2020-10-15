<?php


namespace MUSICAA\controllers;


use MUSICAA\lib\traits\Helper;

class LanguageController extends AbstractController
{
    use Helper;
    public function defaultAction(){
        $_SESSION['lang'] = DEFAULT_LANG;
        if (isset($this->_params[0]) && in_array(LANGS,$this->_params[0]))
        {
            $_SESSION['lang'] = $this->_params[0];
        }

        $this->redirect($_SERVER['HTTP_REFERER']);
    }
}