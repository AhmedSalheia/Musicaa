<?php


namespace MUSICAA\controllers\dashboard;


use MUSICAA\controllers\AbstractController;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\User;

class UsersController extends AbstractController
{
    use Helper;
    public function __construct()
    {
        $this->_data['admin'] = $this->requireAuth('dashboard');
        $this->_data['title'] = 'Users';
    }

    public function defaultAction()
    {
        $this->_data['users'] = User::getAll();
        $this->_view();
    }

    public function detailsAction()
    {
        $this->_data['user'] = User::getByPK($this->filterInt($this->_params[0]));
        $this->_view();
    }
}
