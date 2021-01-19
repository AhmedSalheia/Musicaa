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
use MUSICAA\models\Tracker;
use MUSICAA\models\TrackUserData;
use MUSICAA\models\User;
use MUSICAA\models\Verification;
use MUSICAA\models\youtube\Favorite;
use MUSICAA\models\youtube\FavoriteSong;
use MUSICAA\models\youtube\UserPlaylists;
use MUSICAA\models\youtube\UserPlaylistSongs;

class LoginController extends \MUSICAA\controllers\AbstractController
{
    use Helper;


    public function defaultAction()
    {
        if ($this->_lang !== Null)
        {
            $data = $this->_lang->get();
            if(empty($this->_lang->get())) {
                $this->_lang->load('api.errors.user');
                $data = $this->_lang->get();
            }
            extract($data, EXTR_PREFIX_ALL, 'user');
        }

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
                                    if ($dev->is_primary === 'y') {
                                        $prim = Login::getByCol('deviceId', $dev->id)[0];
                                        break;
                                    }
                                }

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

                            $this->track($user->id,'login',$login->id);

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

    public function resetPasswordAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $email = $this->filterStr($this->checkInput('post','email'));
        $user = User::getByUnique($email);

        if ($user !== false)
        {

            if ($user->verified === 'y')
            {

                $password = $this->enc($this->randText(12));
                $user->password = $password;

                if ($user->save('upd') !== false)
                {
                    $this->trackUserData('user.password.reset',$user->id,$user->password,$password);
                    $verification = '<h4>Password For '.$user->email.' Has Been Reset</h4>';
                    if (!$this->mail($email,$verification,'Musicaa Account Password Change'))
                    {
                        $this->jsonRender($user_emailSendErr,$this->language);
                    }

                    $this->jsonRender($passwordResetSuc,$this->language);
                }else
                {
                    $this->jsonRender($passwordResetErr,$this->language);
                }

            }else{

                $this->jsonRender($user_notVer,$this->language);

            }

        }else
        {

            $this->jsonRender($user_notExists,$this->language);

        }
    }
    /////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
    public function deleteAction()/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
    {/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        foreach (TrackUserData::getAll() as $track)/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        {/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
            $track->delete();/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        }/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        foreach (Tracker::getAll() as $track)/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        {/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
            $track->delete();/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        }/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        foreach (Verification::getAll() as $track)/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        {/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
            $track->delete();/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        }/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        foreach (UserPlaylistSongs::getAll() as $track)/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        {/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
            $track->delete();/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        }/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        foreach (UserPlaylists::getAll() as $track)/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        {/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
            $track->delete();/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        }/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        foreach (FavoriteSong::getAll() as $track)/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        {/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
            $track->delete();/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        }/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        foreach (Favorite::getAll() as $track)/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        {/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
            $track->delete();/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        }/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        foreach (Settings::getAll() as $track)/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        {/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
            $track->delete();/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        }/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        foreach (TokenMod::getAll() as $track)/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        {/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
            $track->delete();/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        }/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        foreach (Login::getAll() as $item)/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        {/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
            var_dump($item->delete());/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        }/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        foreach (User::getAll() as $user)/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        {/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
            var_dump($user->delete());/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
        }/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
    }/////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
    /////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
}
