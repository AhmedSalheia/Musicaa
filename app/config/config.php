<?php

use MUSICAA\models\Languages;

if (!defined('DS')){
    define('DS', DIRECTORY_SEPARATOR);
}

define('APP_PATH', realpath(__DIR__) .DS.'..');
define('VIEWS_PATH', APP_PATH . DS . 'views' . DS);
define('TEMPLATE_PATH', APP_PATH . DS . 'template' . DS);
define('LANG_PATH', APP_PATH . DS . 'languages' . DS);

define('CSS', '/assets/css/');
define('JS', '/assets/js/');
define('IMG', '/assets/images/');
define('INI','./app/ini/');

defined('DATABASE_HOST_NAME')? null : define('DATABASE_HOST_NAME','business29.web-hosting.com');
defined('DATABASE_DB_NAME')? null : define('DATABASE_DB_NAME','progwlfo_musicaabase');
defined('DATABASE_USER_NAME')? null : define('DATABASE_USER_NAME','progwlfo_musicaabase');
defined('DATABASE_PASSWORD')? null : define('DATABASE_PASSWORD','musicaabase123');
defined('DATABASE_PORT_NUMBER')? null : define('DATABASE_PORT_NUMBER',3306);
defined('DATABASE_CONN_DRIVER')? null : define('DATABASE_CONN_DRIVER',1);

defined('DEFAULT_LANG')? null : define('DEFAULT_LANG','en');


$langs = Languages::getAll();
if(is_array($langs))
{
    $arr = [];
    foreach ($langs as $lang)
    {
        $arr[] = $lang->name;
    }
}else{
    $arr = ['en'];
}

defined('LANGS')? null : define('LANGS',$arr);

define('API_VER', ['V1']);
define('SUPPORTED_LANGS',LANGS);
define('REQUEST_SCHEME',['https']);


define('ROLE',[
    'paragraph' =>  [
        'start' =>  '{',
        'end'   =>  '}'
    ],

    'underline' =>  [
        'start' =>  '[',
        'end'   =>  ']'
    ],

    'bold'  =>  [
        'start' =>  '<...',
        'end'   =>  '...>'
    ]
]);

define('KEY','whateverTheFuckthingImustputasAKEY');
define('TOK_KEY','@!#TRPOINTONFOTRFISISVONOPopahunqpvnq56486%*$');

define('TOKEN',array(
    "iat" => time(),
    "iss" => 'musicaa.app'
));

define("API_KEY","AIzaSyCDAVZmLiwJtZfbU-1DyceiBT3Zry7I1js"); //AIzaSyC27cQuXdJQ9Xj72Usu-OOP1R-eAGNuGfM
define("API_Name","Musicaa");

///////////////////  Youtube Things  ///////////////////

define('YOUTUBE_CHANNEL','youtube#channel');
define('YOUTUBE_PLAYLIST','youtube#playlist');
define('YOUTUBE_VIDEO','youtube#video');