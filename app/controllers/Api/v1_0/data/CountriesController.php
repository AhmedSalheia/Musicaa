<?php


namespace MUSICAA\controllers\Api\v1\data;


use MUSICAA\lib\traits\Helper;
use MUSICAA\models\Data;

class CountriesController extends \MUSICAA\controllers\AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('Api.errors.data');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'data');

        $data = Data::get('SELECT * FROM iso_3166_1');

        if ($data !== false)
        {
            $output = [];

            foreach ($data as $datum)
            {
                $arr = [];

                $arr['id'] = $datum->iso;
                $arr['name'] = $datum->printable_name;

                $output[] = $arr;
            }

            $this->jsonRender(['countries' => $output],$this->language);

        }else{

            $this->mail('ahmedsalheia.as@gmail.com','We Have Detected Error In Getting Countries Data From Database','Error In Musicaa App API');
            $this->jsonRender([],$this->language,$data_perror);

        }
    }
}
