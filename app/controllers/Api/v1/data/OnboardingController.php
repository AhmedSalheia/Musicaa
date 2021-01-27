<?php


namespace MUSICAA\controllers\Api\v1\data;


use MUSICAA\controllers\AbstractController;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\Onboarding;

class OnboardingController extends AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('Api.errors.data');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'data');

        $data = Onboarding::getAll();

        if ($data !== false)
        {

            foreach ($data as $datum)
            {
                $datum->img = $_SERVER['REQUEST_SCHEME']. '://' .$_SERVER['HTTP_HOST'].'/getImage/onboarding/'.$datum->img;
            }

            $this->jsonRender(['onboarding' => $data],$this->language);

        }else{

            $this->mail('ahmedsalheia.as@gmail.com','We Have Detected Error In Getting Onboaring Data From Database','Error In Musicaa App API');
            $this->jsonRender([],$this->language,$data_oerror);

        }
    }
}
