<?php


namespace MUSICAA\lib\traits;


use Firebase\JWT\JWT;
use MUSICAA\models\TokenMod;

trait Auth
{

    public function requireAuth($for='api')
    {
        if ($for === 'api')
        {
            if (isset($_SERVER['HTTP_TOKEN']) && $_SERVER['HTTP_TOKEN'] !== '')
            {

                try {

                    $token = JWT::decode($_SERVER['HTTP_TOKEN'],TOK_KEY, array('HS256'));
                    $tokenMod = TokenMod::getByPK($token->data->login_id);
                    if(@(int) $tokenMod->modi === (int) $token->data->MOD)
                    {

                        return $token;

                    }

                    $this->jsonRender([],'en','Bad Token Provided, Please Provide Now Token');

                }catch (\Exception $e)
                {
                    $this->jsonRender([],'en','This Token Is Invalid, Please Verify a valid Token');
                }

            }else{
                $this->jsonRender([],'en','Please Send A Valid Token with the header of the request');
            }
        }elseif($for === 'dashboard')
        {
            if (!isset($_SESSION['musicaa_app_admin_session']))
            {
                $this->redirect(URL.'index/login?ref='.urlencode($_SERVER['REQUEST_URI']));
            }

            return unserialize($_SESSION['musicaa_app_admin_session'],['allowed_classes' => false]);
        }

        return 1;
    }

}
