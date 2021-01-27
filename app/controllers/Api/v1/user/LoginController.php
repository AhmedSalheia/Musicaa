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
                                $this->jsonRender([],$this->language,$user_devSaveErr);
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
                            $this->jsonRender(['user' => $user,'settings' => $set],$this->language);

                        }else{

                            $this->jsonRender([],$this->language,$user_loginSaveErr);
                        }

                    }else
                    {

                        $this->jsonRender([],$this->language,$user_osErr);
                    }

                }else{

                    $this->jsonRender([],$this->language,$user_passErr);

                }

            }else{

                $this->jsonRender([],$this->language,$user_notVer);

            }

        }else
        {

            $this->jsonRender([],$this->language,$user_notExists);

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

            $password = $user->password;
            $user->password = $this->randText(6);
            $user->verified = 'n';

            if ($user->save('upd') !== false)
            {
                $this->trackUserData('user.password.reset',$user->id,$password,$user->password);
                $verification = '<h4>Password For '.$user->email.' Has Been Reset, Reset Code Is <b style="font-size: 20px">'.$user->password.'</b>
                    <br>or refer to this link <a href="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/reset/verify/'.$this->enc($user->email).'/'.$user->password.'">'.substr($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/reset/verify/'.$this->enc($user->email).'/'.$user->password,0,40).'...</a> 
                    <br>Refer To This Link <a href="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/reset/'.$this->enc($user->email).'">If It Was Not You</a></h4>';

                if (!$this->mail($email,$verification,'Musicaa Account Password Change'))
                {
                    $this->jsonRender([],$this->language,$user_emailSendErr);
                }

                $this->jsonRender([],$this->language,$user_passwordResetSuc,true);
            }else
            {
                $this->jsonRender([],$this->language,$user_passwordResetErr);
            }

        }else
        {

            $this->jsonRender([],$this->language,$user_notExists);

        }
    }

    public function resetVerificationAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $email = $this->filterStr($this->checkInput('post','email'));
        $ver = $this->filterStr($this->checkInput('post','verify_code'));

        $user = User::getByUnique($email);

        if ($user !== false)
        {

            if ($ver === $user->password)
            {
                $user->verified = 'y';

                if ($user->save('upd') !== false)
                {
                    $this->trackUserData('user.password.ver',$user->id,'n','y');

                    $this->jsonRender([],$this->language,$user_passwordResetSuc,true);
                }else
                {
                    $this->jsonRender([],$this->language,$user_updUserErr);
                }
            }else
            {
                $this->jsonRender([],$this->language,$user_verCodeErr);
            }

        }else
        {

            $this->jsonRender([],$this->language,$user_notExists);

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
