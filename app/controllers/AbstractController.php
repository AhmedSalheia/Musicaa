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
    protected $type;

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
    public function setType($type)
    {
        $this->type = $type;
        $this->_template->setType($this->type);
    }

    public function getNotFound($type)
    {
        $parts = $this->_template->getParts($this->type);
        $for = TEMPLATE_PATH[$this->type] ?? TEMPLATE_PATH['public'];
        if (!file_exists($for)) {
            $for = TEMPLATE_PATH['public'];
        }

        $title = '404 NOT FOUND';
        require_once $for . 'templateheaderstart.php';

        if (isset($parts['header']['css']))
        {
            foreach ($parts['header']['css'] as $css)
            {
                echo "<link rel='stylesheet' href='".$css."' />";
            }
        }
        if (isset($parts['header']['js']))
        {
            foreach ($parts['header']['js'] as $js)
            {
                echo "<script src='".$js."'></script>";
            }
        }

        require_once $for . 'templateheaderend.php';

        if (file_exists(VIEWS_PATH[$this->type] . 'notfound' . DS . strtolower($type) .'.view.php'))
        {
            require_once VIEWS_PATH[$this->type] . 'notfound' . DS . strtolower($type) .'.view.php';
        }else
        {
            require_once VIEWS_PATH['public'] . 'notfound' . DS . strtolower($type) .'.view.php';
        }
        if (isset($parts['footer']['js']))
        {
            foreach ($parts['footer']['js'] as $js)
            {
                echo "<script src='".$js."'></script>";
            }
        }
        require_once $for . 'templatefooter.php';
    }

    protected function _view($block=[],$allow=[]){
        if ($this->_action == FrontController::NOT_FOUND_ACTION){

            $this->getNotFound('notfound');

        }else{
            $view = VIEWS_PATH[$this->type] . str_replace('dashboard\\','',$this->_controller) . DS . $this->_action. '.view.php';
            if (file_exists($view)){
                $this->_data = array_merge($this->_data, $this->_lang->get());

                $this->_template->setActionViewFile($view);
                $this->_template->setAppData($this->_data);

                $this->_template->renderApp($block,$allow);
            }else{

                $this->getNotFound('noview');

            }
        }

    }
}
