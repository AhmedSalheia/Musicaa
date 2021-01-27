<?php


namespace MUSICAA\lib\traits;


trait JsonFuncions
{

    public function jsonRender(array $data,$language,$message=null,$status=NULL){

        if ($status === NULL)
        {
            $status = !empty($data);
        }
        http_response_code(($status)? 200:400);

        $output = [
            'response' => [
                'message'   =>  $message,
                'data'  => $data
            ],
            'status' => $status,
            'Content-Language' => $language
        ];

        echo json_encode($output,JSON_UNESCAPED_SLASHES);
        exit();
    }

}
