<?php


namespace MUSICAA\controllers\dashboard;


use MUSICAA\controllers\AbstractController;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\Data;
use MUSICAA\models\Languages;
use MUSICAA\models\Theme;

class SettingsController extends AbstractController
{
    use Helper;
    public function __construct()
    {
        $this->_data['admin'] = $this->requireAuth('dashboard');
        $this->_data['title'] = 'Settings';
    }

    public function defaultAction()
    {

    }

    public function privacyAction()
    {
        $privacy = Data::getByPK('privacy');
        if (isset($_POST['sub']))
        {
            $compose = $this->checkInput('post','compose');

            $privacy->data = $compose;

            if ($privacy->save() === false)
            {
                echo 'Error >>>> Could Not Save Your Data, Please Try Again Later, Or Send The Code To The Support: PRIV_SAVE_ERR';
                exit();
            }
        }

        $this->_data['data'] = $privacy;
        $this->_data['title'] .= ' - Privacy';
        $this->_view();
    }

    public function termsAction()
    {
        $terms = Data::getByPK('terms');
        if (isset($_POST['sub']))
        {
            $compose = $this->checkInput('post','compose');

            $terms->data = $compose;

            if ($terms->save() === false)
            {
                echo 'Error >>>> Could Not Save Your Data, Please Try Again Later, Or Send The Code To The Support: TERM_SAVE_ERR';
                exit();
            }
        }

        $this->_data['data'] = $terms;
        $this->_data['title'] .= ' - Terms&Conditions';
        $this->_view();
    }

    public function themesAction()
    {
        $this->_data['themes'] = Theme::getAll();
        $this->_data['title'] .= ' - Themes';
        $this->_view([],['header'=>['css'=>['https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css']]]);
    }

    public function langsAction()
    {
        $this->_data['langs'] = Languages::getAll();
        $this->_data['title'] .= ' - Languages';
        $this->_view([],['header'=>['css'=>['https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css']]]);
    }

    public function osAction()
    {
        $this->_data['themes'] = Theme::getAll();
        $this->_data['title'] .= ' - OS';
        $this->_view([],['header'=>['css'=>['https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css']]]);
    }

    public function dataAction()
    {
        if (isset($_POST['sub']))
        {
            switch ($this->filterStr($_POST['sub']))
            {
                case 'login':

                    $email = $this->checkInput('post','email');
                    $password = $this->checkInput('post','password');
                    $name = $this->checkInput('post','name');
                    $img = 'admin.jpg';

                    $fp = fopen(INI.'login.ini','wb+');
                    $content = "email='".$this->enc($email)."'\npassword='".$this->enc($password)."'\nusername='$name'\nimg='$img'";
                    fwrite($fp,$content);
                    fclose($fp);

                    $this->redirect(URL.'index/logout');

                    break;
            }
        }

        $login = parse_ini_file(INI."login.ini");
        $this->_data['login'] = ['email' => $this->dec($login['email']), 'password' => $this->dec($login['password']),'name' => $login['username']];
        $this->_view();
    }
}
