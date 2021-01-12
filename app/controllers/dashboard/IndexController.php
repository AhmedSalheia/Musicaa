<?php

namespace MUSICAA\controllers\dashboard;

use MUSICAA\controllers\AbstractController;
use MUSICAA\lib\traits\Helper;

class IndexController extends AbstractController
{
    use Helper;
    public function __construct()
    {
        $this->_data['title'] = 'Dashboard';
    }

    public function defaultAction(){
        $this->_data['admin'] = $this->requireAuth('dashboard');
        $this->_view();
    }

    public function loginAction()
    {
        $adm = [];
        if (isset($_POST['sub']))
        {
            $adm = array(
                'email' => $this->filterEmail($this->checkInput('post','username')),
                'password' => $this->filterStr($this->checkInput('post','password'))
            );

        }elseif (isset($_COOKIE['musicaa_app_cookie']))
        {
            $adm = unserialize($_COOKIE['musicaa_app_cookie']);
            $adm['email'] = $this->dec($adm['email']);
            $adm['password'] = $this->dec($adm['password']);
        }

        if ($adm !== [])
        {
            $data = parse_ini_file(INI.'login.ini');

            if ($adm['email'] === $this->dec($data['email']))
            {
                if ($adm['password'] === $this->dec($data['password']))
                {
                    $ref = [
                        'email' => $adm['email'],
                        'name'  => $data['username'],
                        'img'   => $data['img']
                    ];
                    $_SESSION['musicaa_app_admin_session'] = serialize($ref);
                    if (isset($_POST['inputCheckboxesCall']))
                    {
                        $cok = [
                            'email' => $data['email'],
                            'password'  => $data['password']
                        ];
                        \setcookie('musicaa_app_cookie',serialize($cok),time()+60*60*24*30);
                    }

                    if (isset($_GET['ref'])){
                        $this->redirect($_GET['ref']);
                    }else
                    {
                        $this->redirect(URL);
                    }
                }else
                {
                    $_SESSION['msg'] = json_encode(['message' => 'Wrong Username Or Password']);
                }
            }else
            {
                $_SESSION['msg'] = json_encode(['message' => 'Wrong Username Or Password']);
            }
        }

        $this->_view(['blocks'=>['sidebar','header','footer','wrapperstart','wrapperend'],'footer'=>['js' => ['myScript']]]);
    }

    public function logoutAction()
    {
        unset($_SESSION['musicaa_app_admin_session']);
        setcookie('musicaa_app_cookie','',time()-100);
        $this->redirect(URL.'index');
    }
}
