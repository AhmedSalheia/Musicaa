<?php


namespace MUSICAA\controllers\Api\v1\data;


use MUSICAA\lib\traits\Helper;
use MUSICAA\models\GenderLabels;
use MUSICAA\models\Genders;

class GendersController extends \MUSICAA\controllers\AbstractController
{

    use Helper;

    public function defaultAction()
    {

        $this->_lang->load('Api.errors.data');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'data');

        $data = Genders::getAll();
        $labels = GenderLabels::getByPK($this->language);

        if ($data !== false && $labels !== false)
        {

            foreach ($data as $datum)
            {
                $datum->gender = $labels->{$datum->gender};
            }

            $this->jsonRender(['data' => $data],$this->language);

        }else{

            $this->mail('ahmedsalheia.as@gmail.com','We Have Detected Error In Getting Genders Data From Database','Error In Musicaa App API');
            $this->jsonRender($data_gerror,$this->language);

        }

    }

}
