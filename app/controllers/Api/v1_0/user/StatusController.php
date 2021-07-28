<?php


namespace MUSICAA\controllers\Api\v1\user;


use MUSICAA\lib\traits\Helper;
use MUSICAA\models\Status;

class StatusController extends \MUSICAA\controllers\AbstractController
{

    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $token = $this->requireAuth();
        $loginId= $token->data->login_id;

        $status = Status::get('SELECT * FROM status WHERE loginId="'.$loginId.'" AND to_time="0000-00-00 00:00:00"');
        if (!empty($status))
        {

            $status = $status[0];
            $status->to_time = date('Y-m-d H:i:s');

            if ($status->save() !== false)
            {
                $this->jsonRender([],$this->language,$user_statusCloseSuc,true);

            }else
            {
                $this->jsonRender([],$this->language,$user_statusSaveErr);
            }

        }else
        {
            $this->jsonRender([],$this->language,$user_noStatusErr);
        }


    }

    public function changeAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $token = $this->requireAuth();
        $loginId= $token->data->login_id;

        $status = Status::get('SELECT * FROM status WHERE loginId="'.$loginId.'" AND to_time="0000-00-00 00:00:00"');
        if (!empty($status))
        {
            $status = $status[0];

            $stat = $this->filterStr($this->checkInput('post','status_to'));

            if ($status->status === $stat)
            {
                unset($status->from_time, $status->to_time, $status->loginId);
                $this->jsonRender(['active_status' => $status],$this->language,$user_sameStatusErr, false);
            }

            $status->to_time = date('Y-m-d H:i:s');
            if ($status->save() !== false)
            {

                $status = new Status();
                $status->from_time = date('Y-m-d H:i:s');
                $status->status = $stat;
                $status->loginId = $loginId;

                if ($status->save() !== false)
                {
                    unset($status->from_time, $status->to_time, $status->loginId);
                    $this->jsonRender(['active_status' => $status],$this->language,$user_changeStatusSuc.$stat);
                }else
                {
                    $this->jsonRender([],$this->language,$user_statusSaveErr);
                }

            }else
            {
                $this->jsonRender([],$this->language,$user_statusSaveErr);
            }

        }else
        {
            $this->jsonRender([],$this->language,$user_noStatusErr);
        }

    }

    public function newAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $token = $this->requireAuth();
        $loginId= $token->data->login_id;

        $status = Status::get('SELECT * FROM status WHERE loginId="'.$loginId.'" AND to_time="0000-00-00 00:00:00"');
        if (empty($status))
        {
                $status = new Status();
                $status->loginId = $token->data->login_id;
                $status->from_time = date('Y-m-d H:i:s');
                $status->status = 'Active';

                if ($status->save() !== false)
                {

                    unset($status->from_time, $status->to_time, $status->loginId);
                    $this->jsonRender(['status' => $status],$this->language);
                }else
                {
                    $this->jsonRender([],$this->language,$user_statusSaveErr);
                }

        }else
        {
            $status = $status[0];
            unset($status->from_time, $status->to_time, $status->loginId);
            $this->jsonRender(['active_status' => $status],$this->language,$user_statusNewErr,false);
        }

    }

}
