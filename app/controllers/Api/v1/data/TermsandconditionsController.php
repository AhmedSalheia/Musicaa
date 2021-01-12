<?php


namespace MUSICAA\controllers\dashboard\dashboard\dashboard\Api\v1\data;


use MUSICAA\lib\traits\Helper;
use MUSICAA\models\Data;

class TermsAndConditionsController extends \MUSICAA\controllers\dashboard\dashboard\dashboard\AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('Api.errors.data');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'data');
        $data = Data::getByPK('terms');

        if ($data !== false)
        {
            $data->id = 'Terms&Conditions';
            $this->jsonRender(['data' => $data, 'role' => ROLE],$this->language);
        }else{

            $this->mail('ahmedsalheia.as@gmail.com','We Have Detected Error In Getting Terms Data From Database','Error In Musicaa App API');
            $this->jsonRender($data_terror,$this->language);

        }
    }
}
