<?php


namespace MUSICAA\controllers\api\v1\data;


use MUSICAA\lib\traits\Helper;
use MUSICAA\models\Data;

class TermsAndConditionsController extends \MUSICAA\controllers\AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('api.errors.data');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'data');
        $data = Data::getByPK('terms');

        if ($data !== false)
        {
            $data->id = 'Terms&Conditions';
            $this->jsonRender(['data' => $data, 'role' => ROLE],$this->language);
        }else{

            $this->jsonRender($data_terror,$this->language);

        }
    }
}