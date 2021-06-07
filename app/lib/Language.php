<?php


namespace MUSICAA\lib;


class Language
{
    private $_dictionary = [];
    private $_paths = [];

    public function load($path){
        $defaultlanguage = DEFAULT_LANG;
        if(isset($_SESSION['lang'])){
            $defaultlanguage = $_SESSION['lang'];
        }
        $langpath = LANG_PATH . $defaultlanguage . DS . str_replace('.', DS, $path) . '.lang.php';
        if (!in_array($langpath,$this->_paths))
        {
            if (file_exists($langpath)){
                array_push($this->_paths,$langpath);
                require_once $langpath;
                if (is_array($_) && !empty($_)){
                    foreach ($_ as $key => $value){
                        $this->_dictionary[$key] = $value;
                    }
                }
            }else{
                trigger_error('Sorry The Language File Does Not Exist',E_USER_WARNING);
            }
        }
    }

    public function get(){
        return $this->_dictionary;
    }
}