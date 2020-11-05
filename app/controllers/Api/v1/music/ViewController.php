<?php


namespace MUSICAA\controllers\Api\v1\music;


use MUSICAA\lib\traits\Helper;

class ViewController extends \MUSICAA\models\AbstractModel
{
    use Helper;

    public function defaultAction()
    {
        $token = $this->requireAuth();

        $id = $this->filterStr($this->checkInput('post','id'));
        $type = NULL;
        if (isset($_POST['type']))
        {
            $type = $this->filterStr($this->checkInput('post','type'));
        }

        if ($type === NULL)
        {

        }else
        {
            switch ($type)
            {
                u
            }
        }

    }

}