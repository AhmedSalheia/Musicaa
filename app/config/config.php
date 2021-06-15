<?php

use MUSICAA\models\Languages;

if (!defined('DS')){
    define('DS', DIRECTORY_SEPARATOR);
}

/*
 * DATABASE CONSTS
 * **/

defined('DATABASE_HOST_NAME')? null : define('DATABASE_HOST_NAME','localhost'); //business29.web-hosting.com
defined('DATABASE_DB_NAME')? null : define('DATABASE_DB_NAME','progwlfo_musicaabase');
defined('DATABASE_USER_NAME')? null : define('DATABASE_USER_NAME','progwlfo_musicaabase'); //progwlfo_musicaabase
defined('DATABASE_PASSWORD')? null : define('DATABASE_PASSWORD','musicaabase123'); //musicaabase123
defined('DATABASE_PORT_NUMBER')? null : define('DATABASE_PORT_NUMBER',3306);
defined('DATABASE_CONN_DRIVER')? null : define('DATABASE_CONN_DRIVER',1);




/*
 * FILES CONSTS
 * **/

define('APP_PATH', realpath(__DIR__) .DS.'..');
define("SPECIALS",["api",'dashboard']);
define('VIEWS_PATH', [
    'public'    =>  APP_PATH . DS . 'views' . DS,
    'dashboard' =>  APP_PATH . DS . 'views' . DS . 'dashboard' . DS
]);
define('TEMPLATE_PATH', [
    'public'    =>  APP_PATH . DS . 'template' . DS,
    'dashboard' =>  APP_PATH . DS . 'template' . DS . 'dashboard' . DS
]);
define('LANG_PATH', APP_PATH . DS . 'languages' . DS);



/*
 * FRONT CONSTS
 * **/
define('CSS', '/assets/css/');
define('JS', '/assets/js/');
define('IMG', '/assets/images/');
define('DASH_IMG', '/assets/static/images/');
define('INI','../app/ini/');

define('URL','/dashboard/');


/*
 * LANGUAGE CONSTS
 * **/
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




/*
 * API CONSTS
 ***/

define('API_VER', ['V1']);
define('CURRENT_VER', 'V1');
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

define("API_Name","Musicaa");

///////////////////  Youtube Things  ///////////////////

$defaultTokem = [new \stdClass()];$defaultTokem[0]->TOKEN = '';
$token = \MUSICAA\models\youtube\TokenThings\Tokens::getByCol('is_prim','y')?:$defaultTokem;
define('YOUTUBE_TOKEN', $token[0]->TOKEN);
define('YOUTUBE_CHANNEL','youtube#channel');
define('YOUTUBE_PLAYLIST','youtube#playlist');
define('YOUTUBE_VIDEO','youtube#video');
