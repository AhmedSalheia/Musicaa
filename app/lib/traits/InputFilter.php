<?php
namespace MUSICAA\lib\traits;


trait InputFilter
{
    use JsonFuncions;

    public function filterInt($input){
        return (int) filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    public function filterFloat($input){
        return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
    }

    public function filterStr($input){
        return htmlentities(strip_tags($input), ENT_QUOTES,"UTF-8");
    }

    public function filterEmail($input){
        return filter_var($input,FILTER_VALIDATE_EMAIL);
    }

    public function checkInput($method,$input)
    {
        if (strtolower($_SERVER['REQUEST_METHOD']) === strtolower($method))
        {
            if (isset($_REQUEST[$input]) && $_REQUEST[$input] !== '')
            {

                return $_REQUEST[$input];

            }

            $this->jsonRender(['error_for' => $input],'en',$input.' Not Provided',false);
        }else{
            $this->jsonRender([],'en','Please Provide a Valid request, The Request type must be '.strtoupper($method));
        }
    }
}
