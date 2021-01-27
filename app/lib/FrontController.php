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
    private $type='public';

    public function __construct(Template $template , Language $lang)
    {
        $this->_lang = $lang;
        $this->_template = $template;
        $this->_parseUrl();
    }

    private function _parseUrl()
    {
        $url = explode('/',trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'), 3);

        if (isset($url[0]) && $url[0] != '' && !in_array(strtolower($url[0]),SPECIALS)){

            $this->_controller = $url[0];

            if (isset($url[1]) && $url[1] != ''){
                $this->_action = $url[1];
            }

            if (isset($url[2]) && $url[2] != ''){
                $this->_params = explode('/',$url[2]);
            }

        }elseif(isset($url[0]) && $url[0] != ''){

            $url = strtolower($url[0]);
            $this->type = ((isset(VIEWS_PATH[$url]) && VIEWS_PATH[$url]!== NULL)? $url: 'public');

            switch ($url)
            {
                case 'api':
                    header('Content-Type: application/json');

                    if (!isset($_SERVER['REQUEST_SCHEME']) || !in_array($_SERVER['REQUEST_SCHEME'],REQUEST_SCHEME)){
                        $supported = '';
                        foreach (REQUEST_SCHEME as $item) {
                            $supported .= ','.$item;
                        }
                        $this->jsonRender([],$this->language,'Sorry We Don\'t Provide the Wanted http Schema, we recommend using: '.trim($supported,',').'.');
                    }

                    $url = explode('/',trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'), 6);

                    if (isset($url[1]) && $url[1] !== ''){
                        if (in_array(strtoupper($url[1]),API_VER)){
                            $version = implode('_',explode('.',$url[1]));
                        }else{
                            $this->jsonRender([],$this->language,'The Selected Version Is Not Valid');
                        }
                    }else{
                        $this->jsonRender([],$this->language,'No Version Selected');
                    }

                    if (isset($url[2]) && $url[2] !== ''){
                        if (file_exists('../app/controllers/Api/'.$version.'/'.$url[2].'/'))
                        {
                            $category = $url[2];
                        }else{
                            $this->jsonRender([],$this->language,'The Wanted Category Doesn\'t Exist');
                        }
                    }else{
                        $this->jsonRender([],$this->language,'No Category Selected');
                    }

                    if (isset($url[3]) && $url[3] !== '')
                    {
                        $this->_controller = 'api\\'.$version.'\\'.$category.'\\'. ucfirst(strtolower($url[3]));
                    }else{
                        $this->jsonRender([],$this->language,'No Section Selected');
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
                        $this->jsonRender([],$this->language,'Please Send A Supported Language with the header of the request');
                    }

                    break;
                case 'dashboard':

                    $url = explode('/',trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'), 4);

                    $this->_controller = 'dashboard\\'.$this->_controller;
                    if (isset($url[1]) && $url[1] !== ''){

                        $this->_controller = 'dashboard\\'.$url[1];

                        if (isset($url[2]) && $url[2] !== ''){
                            $this->_action = $url[2];
                        }

                        if (isset($url[3]) && $url[3] !== ''){
                            $this->_params = explode('/',$url[3]);
                        }

                    }

                    break;
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
                $this->jsonRender([],$this->language,'The Wanted Section Doesn\'t Exist');
            }
        }

        $controller = new $controllerClassName;
        if (!method_exists($controller, $actionName)){
            if (str_word_count($this->_controller,1)[0] !== 'api')
            {
                $this->_action = $actionName = self::NOT_FOUND_ACTION;
            }else{
                header("HTTP/1.1 404 Not Found");
                $this->jsonRender([],$this->language,'The Wanted Action Doesn\'t Exist');
            }
        }

        $controller->setController($this->_controller);
        self::$controller = $this->_controller;
        $controller->setAction($this->_action);
        $controller->setParams($this->_params);
        $controller->setTemplate($this->_template);
        $controller->setLang($this->_lang);
        $controller->setLanguage($this->language);
        $controller->setType($this->type);

        $controller->$actionName();
    }
}
