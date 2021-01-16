<?php


namespace MUSICAA\controllers\Api\v1\user;


use MUSICAA\controllers\AbstractController;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\GenderLabels;
use MUSICAA\models\Genders;
use MUSICAA\models\User;
use MUSICAA\models\Verification;

class RegisterController extends AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $firstname =    $this->checkInput('post','firstname');
        $lastname  =    $this->checkInput('post','lastname');
        $phone     =    $this->checkInput('post','phone');
        $email     =    $this->checkInput('post','email');
        $password  =    $this->enc($this->checkInput('post','password'));
        $country   =    $this->checkInput('post','country');
        $gender    =    ucwords($this->checkInput('post','gender'));

        $genders = GenderLabels::getByPK($this->language);
        foreach ($genders as $key => $val)
        {
            if ($gender === $val)
            {
                $gend = Genders::getByUnique($key);
                $gender = $gend->id;
            }
        }

        $user = new User();
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->phone = $phone;
        $user->email = strtolower($email);
        $user->password = $password;
        $user->country = USER::get('Select * from iso_3166_1 Where iso LIKE "%'.$country.'%"')[0]->iso;
        $user->gender = $gender;

        $save = $user->save();

        if (!is_object($save))
        {

            if ($save === true)
            {
                $this->track($user->id,'register','user.register');

                $ver = $this->randText(6);
                $verify = new Verification();
                $verify->userId = $user->id;
                $verify->verification = $ver;

                /////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
                if ($email === 'test@test.com')                                                                                 //////
                {                                                                                                               //////
                    $verify->verification = "111111";                                                                           //////
                }                                                                                                               //////
                /////////////////////////////////////////////// FOR DEVELOPING ONLY //////////////////////////////////////////////////
                $save = $verify->save();
                if ($save === true)
                {
                    $verification = '<h4>Your VerificationController Code is </h4><h1>'.$ver.'</h1>';
                    $mail = $this->mail($email,$verification,'Verify Your Account');

                    if (!$mail)
                    {
                        $this->jsonRender($user_emailSendErr,$this->language);
                    }
                }

                $this->jsonRender($user_registerSuc,$this->language,true);

            }else{

                $this->jsonRender($user_cantRegister,$this->language);

            }

        }else
        {
            $this->jsonRender($user_already,$this->language);
        }
    }
}
