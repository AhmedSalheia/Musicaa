<?php
    namespace MUSICAA;
    use MUSICAA\lib\FrontController;
    use MUSICAA\lib\Language;
    use MUSICAA\lib\Template;
    use MUSICAA\models\youtube\TokenThings\Tokens;

    session_start();

    if (!defined('DS')){
        define('DS', DIRECTORY_SEPARATOR);
    }

    require_once '..'.DS.'vendor' . DS . 'autoload.php';
    require_once '..'.DS.'app' . DS . 'config' . DS . 'config.php';
    $template_parts = require '..'.DS.'app' . DS . 'config' . DS . 'templateconfig.php';

    if (!isset($_SESSION['lang'])){
        $_SESSION['lang'] = DEFAULT_LANG;
    }

    $template = new Template($template_parts);
    $lang = new Language();

    try{

        $fp = fopen('log.php','ab');
        fwrite($fp,$_SERVER['REQUEST_METHOD']);
        fclose($fp);

        $frontController = new FrontController($template, $lang);
        $frontController->dispatch();
    }catch (\Exception $e)
    {
        $tokens = Tokens::getAll();

        token:

        shuffle($tokens);
        if ($tokens[0]->TOKEN === YOUTUBE_TOKEN)
        {
            goto token;
        }else
        {
            $oldToken = Tokens::getByCol('TOKEN',YOUTUBE_TOKEN)[0];
            $newToken = $tokens[0];

            $oldToken->is_prim = 'n';
            $newToken->is_prim = 'y';

            if($oldToken->save() !== false)
            {
                if ($newToken->save() !== false)
                {
                    header("Refresh:0");
                }
            }
        }
    }
