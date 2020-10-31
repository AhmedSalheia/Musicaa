<?php


namespace MUSICAA\lib\traits;


use Firebase\JWT\JWT;
use MUSICAA\models\TokenMod;

trait Auth
{

    public function requireAuth()
    {

        if (isset($_SERVER['HTTP_TOKEN']) && $_SERVER['HTTP_TOKEN'] !== '')
        {

            try {

                $token = JWT::decode($_SERVER['HTTP_TOKEN'],TOK_KEY, array('HS256'));
                $tokenMod = TokenMod::getByPK($token->data->login_id);
                if((int) $tokenMod->modi === (int) $token->data->MOD)
                {

                    return $token;

                }else
                {
                    $this->jsonRender('Bad Token Provided, Please Provide Now Token','en');
                }

            }catch (\Exception $e)
            {
                $this->jsonRender('This Token Is Invalid, Please Verify a valid Token','en');
            }

        }else{
            $this->jsonRender('Please Send A Valid Toke with the header of the request','en');
        }
        return 1;
    }

}