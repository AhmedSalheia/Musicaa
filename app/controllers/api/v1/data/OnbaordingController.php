<?php


namespace MUSICAA\controllers\api\v1\data;


use MUSICAA\controllers\AbstractController;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\Data;

class OnbaordingController extends AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('api.errors.data');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'data');

        $data = Data::getByPK('privacy');

        if ($data !== false)
        {
            $data->id = 'Privacy Policy';
            $this->jsonRender(['data' => $data, 'role' => ROLE],$this->language);
        }else{

            $this->jsonRender($data_perror,$this->language);

        }
    }
}