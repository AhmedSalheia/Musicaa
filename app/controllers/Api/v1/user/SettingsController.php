<?php


namespace MUSICAA\controllers\Api\v1\user;


use MUSICAA\lib\traits\Helper;

class SettingsController extends \MUSICAA\controllers\AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $token = $this->requireAuth();
        $loginId = $token->data->login_id;

        var_dump($this->toOct(111));
    }

}