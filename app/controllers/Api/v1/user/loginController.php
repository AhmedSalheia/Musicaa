<?php


namespace MUSICAA\controllers\Api\v1\user;


use Firebase\JWT\JWT;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\Devices;
use MUSICAA\models\Login;
use MUSICAA\models\OS;
use MUSICAA\models\User;

class loginController extends \MUSICAA\controllers\AbstractController
{
    use Helper;


    public function defaultAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $email = $this->checkInput('post','email');
        $pass = $this->checkInput('post','password');
        $device = $this->checkInput('post','device');
        $UUID = $this->checkInput('post','UUID');
        $name = $this->checkInput('post','device_name');


        $user = User::getByUnique($email);

        if ($user !== false)
        {

            if ($pass === $this->dec($user->password))
            {

                $os = OS::getByUnique(ucfirst(strtolower($device)));

                if ($os !== false)
                {

                    $device = Devices::getByUnique($UUID);

                    if ($device === false)
                    {
                        $device = new Devices();
                        $device->OS = $os->OS;
                        $device->is_primary = 'y';
                        $device->UUID = $UUID;
                        $device->name = $name;

                        if ($device->save() === false)
                        {
                            $this->jsonRender($user_devSaveErr,$this->language);
                        }
                    }

                    $login = new Login();
                    $login->userId = $user->id;
                    $login->deviceId = $device->id;

                    if ($login->save() !== false)
                    {

                        $token_B = array(
                            TOKEN,
                            "data" => array(
                                "user_id"   => $user->id,
                                "device_id" => $device->id,
                                ""
                            )
                        );

                        JWT::encode($token_B,TOK_KEY);

                    }else{
                        $this->jsonRender($user_loginSaveErr,$this->language);
                    }

                }else
                {
                    $this->jsonRender($user_osErr,$this->language);
                }

            }else{

                $this->jsonRender($user_passErr,$this->language);

            }
        }else
        {

            $this->jsonRender($user_notExists,$this->language);

        }

    }
}