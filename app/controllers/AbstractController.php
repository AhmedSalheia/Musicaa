<?php

namespace MUSICAA\controllers;

use MUSICAA\lib\FrontController;
use MUSICAA\models\youtube\TokenThings\Tokens;

class AbstractController
{
    private $_controller = 'index';
    private $_action = 'default';
    protected $_params = array();
    protected $_template;
    protected $_lang;
    protected $language='en';
    protected $client;
    public $API_KEY = YOUTUBE_TOKEN;
    protected $service;

    public function __construct()
    {
        ini_set('max_execution_time', 0);

        $this->client = new \Google_Client();
        $this->client->setApplicationName(API_Name);
        $this->client->setDeveloperKey($this->API_KEY);

        $this->service = new \Google_Service_YouTube($this->client);
    }

    protected $_data = [];

    public function setController($controller)
    {
        $this->_controller = $controller;
    }

    public function setAction($action)
    {
        $this->_action = $action;
    }

    public function setParams($params)
    {
        $this->_params = $params;
    }

    public function setTemplate($template)
    {
        $this->_template = $template;
    }

    public function setLang($lang)
    {
        $this->_lang = $lang;
    }

    public function notFoundAction(){
        $this->_view();
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    protected function _view(){
        if ($this->_action == FrontController::NOT_FOUND_ACTION){
            require_once VIEWS_PATH . 'notfound' . DS . 'notfound.view.php';
        }else{
            $view = VIEWS_PATH . $this->_controller . DS . $this->_action. '.view.php';
            if (file_exists($view)){
                $this->_data = array_merge($this->_data, $this->_lang->get());

                $this->_template->setActionViewFile($view);
                $this->_template->setAppData($this->_data);

                $this->_template->renderApp();
            }else{
                require_once VIEWS_PATH . 'notfound' . DS . 'noview.view.php';
            }
        }

    }
}
