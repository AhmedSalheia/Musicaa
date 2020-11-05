<?php

namespace MUSICAA\lib;

use MUSICAA\lib\traits\Helper;

class FrontController
{
    use Helper;

    const NOT_FOUND_ACTION = 'notFoundAction';
    const NOT_FOUND_CONTROLLER = 'MUSICAA\controllers\NotFoundController';

    private $_controller = 'index';
    private $_action = 'default';
    public $_params = array();

    public static $controller;

    private $_template;
    private $_lang;
    private $language='en';

    public function __construct(Template $template , Language $lang)
    {
        $this->_lang = $lang;
        $this->_template = $template;
        $this->_parseUrl();
    }

    private function _parseUrl()
    {
        $url = explode('/',trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'), 3);

        if (isset($url[0]) && $url[0] != '' && strtolower($url[0]) !== 'api'){

            $this->_controller = $url[0];

            if (isset($url[1]) && $url[1] != ''){
                $this->_action = $url[1];
            }

            if (isset($url[2]) && $url[2] != ''){
                $this->_params = explode('/',$url[2]);
            }

        }elseif(isset($url[0]) && $url[0] != '' && strtolower($url[0]) === 'api'){
            header('Content-Type: application/json');

            $url = explode('/',trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'), 6);

            if (isset($url[1]) && $url[1] !== '' && in_array(strtoupper($url[1]),API_VER)){
                $version = $url[1];
            }else{
                $this->jsonRender('No Version Selected',$this->language);
            }

            if (isset($url[2]) && $url[2] !== ''){
                $category = $url[2];
            }else{
                $this->jsonRender('No Category Selected',$this->language);
            }

            if (isset($url[3]) && $url[3] !== '')
            {
                $this->_controller = 'api\\'.$version.'\\'.$category.'\\'. ucfirst(strtolower($url[3]));
            }else{
                $this->jsonRender('No Action Selected',$this->language);
            }


            if (isset($url[4]) && $url[4] !== '')
            {
                $this->_action = $url[4];
            }

            if (isset($url[5]) && $url[5] != ''){
                $this->_params = explode('/',$url[5]);
            }

            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && in_array(strtolower(explode('-',$_SERVER['HTTP_ACCEPT_LANGUAGE'])[0]), SUPPORTED_LANGS)){
                $this->language = strtolower(explode('-',$_SERVER['HTTP_ACCEPT_LANGUAGE'])[0]);
                $_SESSION['lang'] = $this->language;
            }else{
                $this->jsonRender('Please Send A Supported Language with the header of the request',$this->language);
            }

            if (!isset($_SERVER['REQUEST_SCHEME']) || !in_array($_SERVER['REQUEST_SCHEME'],REQUEST_SCHEME)){
                $supported = '';
                foreach (REQUEST_SCHEME as $item) {
                    $supported .= ','.$item;
                }
                $this->jsonRender('Sorry We Don\'t Provide the Wanted http Schema, we recommend using: '.trim($supported,',').'.',$this->language);
            }

        }
    }

    public function dispatch()
    {
        $controllerClassName = 'MUSICAA\controllers\\'.ucfirst($this->_controller).'Controller';
        $actionName = $this->_action . 'Action';

        if (!class_exists($controllerClassName)){
            if (str_word_count($this->_controller,1)[0] !== 'api')
            {
                $controllerClassName = self::NOT_FOUND_CONTROLLER;
            }else{
                header("HTTP/1.1 404 Not Found");
                $this->jsonRender('The Wanted Category Doesn\'t Exist',$this->language);
            }
        }

        $controller = new $controllerClassName;
        if (!method_exists($controller, $actionName)){
            if (str_word_count($this->_controller,1)[0] !== 'api')
            {
                $this->_action = $actionName = self::NOT_FOUND_ACTION;
            }else{
                header("HTTP/1.1 404 Not Found");
                $this->jsonRender('The Wanted Action Doesn\'t Exist',$this->language);
            }
        }

        $controller->setController($this->_controller);
        self::$controller = $this->_controller;
        $controller->setAction($this->_action);
        $controller->setParams($this->_params);
        $controller->setTemplate($this->_template);
        $controller->setLang($this->_lang);
        $controller->setLanguage($this->language);

        $controller->$actionName();
    }
}