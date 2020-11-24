<?php


namespace MUSICAA\controllers\Api\v1\user;


use Firebase\JWT\JWT;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\Data;
use MUSICAA\models\Devices;
use MUSICAA\models\GenderLabels;
use MUSICAA\models\Genders;
use MUSICAA\models\Tracker;
use MUSICAA\models\User;
use MUSICAA\models\Verification;

class ProfileController extends \MUSICAA\controllers\AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $token = $this->requireAuth();

        $user = User::getByPK($token->data->user_id);
        unset($user->password, $user->verified, $user->id);
        $gender = Genders::getByPK($user->gender)->gender;
        $user->gender = GenderLabels::getByPK($this->language)->$gender;
        $user->country = Data::get('SELECT * FROM iso_3166_1 WHERE iso LIKE "%'.$user->country.'%"')[0]->printable_name;

        $this->jsonRender(['data'=>$user],$this->language);
    }

    public function updatedataAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $token = $this->requireAuth();
        $loginId = $token->data->user_id;
        $device = Devices::getByPK($token->data->device_id);

        $user = User::getByPK($token->data->user_id);

        $cond = (isset($this->_params[0]))? $this->_params[0]:'none';

        switch (strtolower($cond))
        {
            default:

                $firstname  = $this->checkInput('post','firstname');
                $middlename = $this->checkInput('post','middlename');
                $lastname   = $this->checkInput('post','lastname');
                $phone      = $this->checkInput('post','phone');
                $country    = USER::get('Select * from iso_3166_1 Where iso LIKE "%'.$this->filterStr($this->checkInput('post','country')).'%"')[0]->iso;
                $gender     = ucwords($this->checkInput('post','gender'));
                $genders = GenderLabels::getByPK($this->language);
                foreach ($genders as $key => $val)
                {
                    if ($gender === $val)
                    {
                        $gend = Genders::getByUnique($key);
                        $gender = $gend->id;
                    }
                }

                if ($firstname !== $user->firstname)
                {
                    $this->trackUserData('firstname',$loginId,$user->firstname,$firstname);
                    $user->firstname = $firstname;
                }

                if ($middlename !== $user->middlename)
                {
                    $this->trackUserData('middlename',$loginId,$user->middlename,$middlename);
                    $user->middlename = $middlename;
                }

                if ($lastname !== $user->lastname)
                {
                    $this->trackUserData('lastname',$loginId,$user->lastname,$lastname);
                    $user->lastname = $lastname;
                }

                if ($phone !== $user->phone)
                {
                    $this->trackUserData('phone',$loginId,$user->phone,$phone);
                    $user->phone = $phone;
                }

                if ($country !== $user->country)
                {
                    $this->trackUserData('country',$loginId,$user->country,$country);
                    $user->country = $country;
                }

                if ($gender !== $user->gender)
                {
                    $this->trackUserData('gender',$loginId,$user->gender,$gender);
                    $user->gender = $gender;
                }

                if (isset($_FILES['img']))
                {
                    $img        = $_FILES['img']['tmp_name'];
                    $img_extn   = array_reverse(explode('.',$_FILES['img']['name']))[0];

                    if ($img !== '')
                    {
                        $name = $this->randText(6).'.'.$img_extn;
                        if (move_uploaded_file($img,'.'.IMG.$name))
                        {
                            $this->trackUserData('img',$loginId,$user->img,$name);
                            $user->img = $name;

                        }else
                        {
                            $this->jsonRender($user_uploadErr,$this->language);
                        }
                    }
                }

                break;




            case 'email':

                $email = $this->checkInput('post','email');

                if ($device->is_primary === 'y'){

                    if ($email !== $user->email)
                    {
                        $this->trackUserData('email',$loginId,$user->email,$email);

                        $user->email = $email;
                        $user->verified = 'n';

                        $ver = $this->randText(6);
                        $verify = new Verification();
                        $verify->userId = $user->id;
                        $verify->verification = $ver;

                        $save = $verify->save();
                        if ($save === true)
                        {
                            $verification = '<h4>Your VerificationController Code is <b>'.$ver.'</b> </h4>';
                            if (!$this->mail($email,$verification,'Verify Your Account'))
                            {
                                $this->jsonRender($user_emailSendErr,$this->language);
                            }
                        }
                    }else
                    {
                        $this->jsonRender($user_emailSameErr,$this->language);
                    }
                }else
                {
                    $this->jsonRender($user_needAdmin,$this->language);
                }

                break;

            case 'password':

                if ($device->is_primary === 'y'){

                        $password = $this->checkInput('post','password');

                        if ($password !== $this->dec($user->password))
                        {
                            $password = $this->enc($password);
                            $this->trackUserData('password',$loginId,$user->password,$password);

                            $user->password = $password;

                        }else
                        {
                            $this->jsonRender($user_passSameErr,$this->language);
                        }
                }else
                {
                    $this->jsonRender($user_needAdmin,$this->language);
                }

                break;
        }



        if ($user->save('upd') !== false)
        {

            $user->token = JWT::encode($token,TOK_KEY);
            $user->country = Data::get('SELECT * FROM iso_3166_1 WHERE iso LIKE "%'.$user->country.'%"')[0]->printable_name;

            $gender = Genders::getByPK($user->gender)->gender;
            $user->gender = GenderLabels::getByPK($this->language)->$gender;

            unset($user->id, $user->verified, $user->password);
            $this->jsonRender(['message' => $user_saveDataSuc,'data' => [$user]],$this->language);

        }else{

            $this->jsonRender($user_errSaveData,$this->language);

        }
    }

}