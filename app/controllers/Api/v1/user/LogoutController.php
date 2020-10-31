<?php


namespace MUSICAA\controllers\Api\v1\user;


use MUSICAA\lib\traits\Helper;
use MUSICAA\models\TokenMod;

class LogoutController extends \MUSICAA\controllers\AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $token = $this->requireAuth();


        $tokenMod = TokenMod::getByPK($token->data->login_id);
        $tokenMod->modi = 0;

        if($tokenMod->save() !== false)
        {

            $this->jsonRender('',$this->language,true);

        }else
        {
            $this->jsonRender($user_logoutErr,$this->language);
        }
    }

}