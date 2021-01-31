<?php


namespace MUSICAA\lib\traits;


trait JsonFuncions
{

    public function jsonRender(array $data,$language,$message='',$status=NULL){

        if ($status === NULL)
        {
            $status = !empty($data);
        }
        http_response_code(($status)? 200:400);

        $output = [
            'response' => [
                'message'   =>  $message
            ],
            'status' => $status,
            'Content-Language' => $language
        ];

        if (!empty($data))
        {
            $output['response']['data'] = $data;
        }

        echo json_encode($output,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        exit();
    }

}
