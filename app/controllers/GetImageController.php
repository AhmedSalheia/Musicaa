<?php


namespace MUSICAA\controllers;


class GetImageController extends AbstractController
{
    public function onboardingAction()
    {

        $file = '.'.IMG.'onboarding/'.($this->_params[0]).'.png';
        if (is_file($file))
        {
            header('Content-type: '.mime_content_type($file));
            readfile($file);
        }

    }
}