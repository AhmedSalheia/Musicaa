<?php


namespace MUSICAA\controllers;


use MUSICAA\lib\traits\Helper;
use MUSICAA\models\User;

class ResetController extends AbstractController
{
    use Helper;

    public function defaultAction()
    {
        if (isset($_POST['sub']))
        {
            $email = $this->checkInput('post','email');

            $resp = $this->sendRequest('post','https://'.$_SERVER['HTTP_HOST'].'/api/'.CURRENT_VER."/user/login/resetPassword/",['email'=>$email]);
            if($resp === false || json_decode($resp)->status === false)
            {
                // TODO:
                //
                //  ADD FAILED ACTION
            }

            $_SESSION['res_email'] = $email;
            $this->redirect('/reset/verify');
        }

        $this->_view();
    }

    public function verifyAction()
    {
        if (!isset($_SESSION['res_email']))
        {
            if (!isset($this->_params[0])){
                $this->redirect('/reset');
            }

            $_SESSION['res_email'] = $this->_params[0];
        }

        $email = $_SESSION['res_email'];

        if (isset($_POST['sub']) || isset($this->_params[0]))
        {
            $ver = $this->_params[1] ?? $this->filterStr($this->checkInput('post','verify_code'));

            $resp = $this->sendRequest('post','https://'.$_SERVER['HTTP_HOST'].'/api/'.CURRENT_VER."/user/login/resetVerification/",['email'=>$email,'verify_code'=>$ver]);
            if($resp === false || json_decode($resp)->status === false)
            {
                var_dump(json_decode($resp));
                // TODO:
                //
                //  ADD FAILED ACTION
            }

            $_SESSION['msg'] = ['status'=>true,'message'=>'verified Successfully'];
            $this->redirect('/reset/changePassword');
        }

        $this->_view();
    }

    public function changePasswordAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        if (!isset($_SESSION['res_email']))
        {
            $this->redirect('/reset');
        }

        if(isset($_POST['sub']))
        {
            $pass = $this->filterStr($this->checkInput('post','password'));
            $rePass = $this->filterStr($this->checkInput('post','rePassword'));

            if ($pass === $rePass)
            {
                $user = User::getByUnique($_SESSION['res_email']);

                if ($user !== false)
                {

                    $user->password = $this->enc($pass);

                    if ($user->save('upd') !== false)
                    {
                        $this->trackUserData('user.password',$user->id,'reset',$user->password);

                        $verification = '<h4>Password Has Been Reset Successfully</h4>';

                        if (!$this->mail($user->email,$verification,'Musicaa Account Password Change'))
                        {
                            $this->jsonRender($user_emailSendErr,$this->language);
                        }

                        $this->jsonRender($user_passwordResetSuc,$this->language,true);
                    }else
                    {
                        $this->jsonRender($user_passwordResetErr,$this->language);
                    }

                }else
                {

                    $this->jsonRender($user_notExists,$this->language);

                }

            }else
            {
                $this->jsonRender($user_passSameErr,$this->language);
            }
        }

        $this->_view();
    }
}