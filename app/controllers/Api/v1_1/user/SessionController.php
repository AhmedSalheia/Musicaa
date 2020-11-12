<?php


namespace MUSICAA\controllers\Api\v1_1\user;


use MUSICAA\controllers\AbstractController;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\Devices;
use MUSICAA\models\Login;
use MUSICAA\models\OS;
use MUSICAA\models\TokenMod;

class SessionController extends AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $token = $this->requireAuth();

        $device = Devices::getByPK($token->data->device_id);

        if ($device->is_primary === 'y'){
            $login = $token->data->login_id;

            $logins = Login::getByUnique($token->data->user_id);
            $output = [];

            if (is_array($logins)) {
                foreach ($logins as $item) {
                    $arr = [];

                    $arr['id'] = $item->id;
                    $arr['current'] = $item->id === $login;

                    $device = Devices::getByPK($item->deviceId);
                    $os = OS::getByPK($device->OS);
                    $device->OS = $os->OS;
                    unset($device->id,$device->is_primary);

                    $arr['device'] = $device;

                    $output[] = $arr;
                }

            }else
            {
                $arr['id'] = $login;
                $arr['current'] = true;

                $os = OS::getByPK($device->OS);
                $device->OS = $os->OS;
                unset($device->id,$device->is_primary);

                $arr['device'] = $device;
                $output['data'] = $arr;
            }

            $this->jsonRender($output, $this->language);
        }else
        {
            $this->jsonRender($user_needAdmin,$this->language);
        }

    }

    public function logoutAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $token = $this->requireAuth();

        $device = Devices::getByPK($token->data->device_id);

        if ($device->is_primary === 'y'){

            $sessionId = $this->filterInt($this->checkInput('post','sessionId'));
            $Seslogin = Login::getByPK($sessionId);

            if ($Seslogin->deviceId === $device->id)
            {

                $logins = Login::getByUnique($token->data->user_id);

                foreach ($logins as $login)
                {
                    $tokenMod = TokenMod::getByPK($login->id);
                    $tokenMod->modi = 0;

                    if ($tokenMod->save() === false)
                    {
                        $this->jsonRender($user_logoutErr,$this->language);
                    }
                }

                $this->jsonRender($user_SeslogoutAllSuc,$this->language,true);

            }else{

                $tokenMod = TokenMod::getByPK($sessionId);
                $tokenMod->modi = 0;

                if ($tokenMod->save())
                {
                    $this->jsonRender($user_SeslogoutSuc, $this->language,true);
                }else{
                    $this->jsonRender($user_logoutErr, $this->language);
                }

            }
        }else
        {
            $this->jsonRender($user_needAdmin,$this->language);
        }
    }
}