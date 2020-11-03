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

    public function checkInput($method,$input)
    {
        $method = strtoupper($method);

        if ($_SERVER['REQUEST_METHOD'] === $method)
        {
            if (isset($_REQUEST[$input]) && $_REQUEST[$input] !== '')
            {

                return $_REQUEST[$input];

            }else
            {
                $this->jsonRender(['message' => $input.' Not Provided','error_for' => $input],'en',false);
            }
        }else{
            $this->jsonRender('Please Provide a Valid request, The Request type must be '.$method,'en');
        }
    }
}