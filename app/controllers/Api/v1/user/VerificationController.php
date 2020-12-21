<?php


namespace MUSICAA\controllers\Api\v1\user;

use \MUSICAA\controllers\AbstractController;
use MUSICAA\lib\Language;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\User;
use MUSICAA\models\Verification;

class VerificationController extends AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $email =$this->checkInput('post','email');
        $code = $this->checkInput('post','ma');

        $user = User::getByUnique(strtolower($email));

        if ($user !== false)
        {

            if ($user->verified === 'n')
            {

                $ver = Verification::getByUnique($user->id);

                if ($ver !== false)
                {

                    if ($code === $ver->verification)
                    {

                        $user->verified = 'y';

                        if ($user->save('upd'))
                        {
                            $ver->delete();
                            $this->track($user->id,'verify','success');

                            $login = new LoginController();
                            $login->setLang($this->_lang);
                            $login->defaultAction();
                            $this->jsonRender(['message' => $user_verifiedSuc],$this->language,true);

                        }else
                        {
                            $this->track($user->id,'verify','Error Save');
                            $this->jsonRender($user_errSaveData,$this->language);
                        }

                    }else
                    {
                        $this->track($user->id,'verify','Error Code');
                        $this->jsonRender($user_verCodeErr,$this->language);

                    }

                }else{
                    $this->jsonRender($user_verErr,$this->language);
                }

            }else
            {
                $this->jsonRender($user_alrVerified,$this->language);
            }

        }else
        {

            $this->jsonRender($user_notExists,$this->language);

        }
    }

    public function resendAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $email =$this->checkInput('post','email');

        $user = User::getByUnique(strtolower($email));

        if ($user !== false)
        {

            if ($user->verified === 'n')
            {

                $ver = Verification::getByUnique($user->id);

                if ($ver !== false)
                {

                    $verification = '<h4>Your VerificationController Code is </h4><h1>'.$ver->verification.'</h1>';
                        $this->mail($email,$verification,'Verify Your Account');

                        $this->jsonRender(['message' => 'Code Sent To Your Email'],$this->language);

                }else{
                    $this->jsonRender($user_verErr,$this->language);
                }

            }else
            {
                $this->jsonRender($user_alrVerified,$this->language);
            }

        }else
        {

            $this->jsonRender($user_notExists,$this->language);

        }
    }
}