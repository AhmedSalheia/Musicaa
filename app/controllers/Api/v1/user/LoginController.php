<?php


namespace MUSICAA\controllers\Api\v1\user;


use Firebase\JWT\JWT;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\Data;
use MUSICAA\models\DefaultSettings;
use MUSICAA\models\Devices;
use MUSICAA\models\GenderLabels;
use MUSICAA\models\Genders;
use MUSICAA\models\Languages;
use MUSICAA\models\Login;
use MUSICAA\models\OS;
use MUSICAA\models\Settings;
use MUSICAA\models\Theme;
use MUSICAA\models\TokenMod;
use MUSICAA\models\User;

class LoginController extends \MUSICAA\controllers\AbstractController
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

            if ($user->verified === 'y')
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
                            $device->OS = $os->id;
                            $device->is_primary = (Login::getByCol('userID',$user->id) !== false)? 'n':'y';
                            $device->UUID = $UUID;
                            $device->name = $name;

                            if ($device->save() === false)
                            {
                                $this->jsonRender($user_devSaveErr,$this->language);
                            }
                        }

                        $login = Login::get('SELECT * FROM login WHERE userId="'.$user->id.'" AND deviceId="'.$device->id.'"');
                        if (is_array($login) && !empty($login))
                        {
                            $login = $login[0];

                        }else
                        {
                            $login = new Login();
                            $login->userId = $user->id;
                            $login->deviceId = $device->id;
                        }

                        $save = $login->save('upd');
                        if ($save !== false)
                        {
                            $tokenMod = TokenMod::getByPK($login->id);
                            if ($tokenMod === false)
                            {
                                $tokenMod = new TokenMod();
                                $tokenMod->loginId = $login->id;
                                $tokenMod->modi = 1;
                            }

                            $token_B = array(
                                TOKEN,
                                "data" => array(
                                    "user_id"   => $user->id,
                                    "user_email"=> $user->email,
                                    "device_id" => $device->id,
                                    "login_id"  => $login->id,
                                    "MOD"       => ++$tokenMod->modi
                                )
                            );

                            $tokenMod->save();

                            $user->token = JWT::encode($token_B,TOK_KEY);
                            $user->country = Data::get('SELECT * FROM iso_3166_1 WHERE iso LIKE "%'.$user->country.'%"')[0]->printable_name;

                            $gender = Genders::getByPK($user->gender)->gender;
                            $user->gender = GenderLabels::getByPK($this->language)->$gender;


                            //////////////////////////////////////////// Start Settings: ////////////////////////////////////////////
                            $prim = Login::getByUnique($user->id);
                            if (is_array($prim)) {
                                foreach ($prim as $item) {
                                    $dev = Devices::getByPK($item->deviceId);
                                    var_dump($dev);
                                    if ($dev->is_primary === 'y') {
                                        $prim = Login::getByCol('deviceId', $dev->id)[0];
                                        break;
                                    }
                                }

//                                var_dump($prim);
                                $set = Settings::getByPK($prim->id);
                                if ($set === false) {
                                    $set = DefaultSettings::getByPK($device->OS);
                                }
                            }else
                            {
                                $set = DefaultSettings::getByPK($device->OS);
                            }
                            (new SettingsController())->decodeSettings($set);
                            //////////////////////////////////////////// End Settings: ////////////////////////////////////////////


                            unset($user->id, $user->verified, $user->password,$set->os,$set->loginId);
                            $this->jsonRender(['data' => $user,'settings' => $set],$this->language);

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

            }else{

                $this->jsonRender($user_notVer,$this->language);

            }

        }else
        {

            $this->jsonRender($user_notExists,$this->language);

        }

    }
}