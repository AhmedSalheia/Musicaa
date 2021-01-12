<?php


namespace MUSICAA\controllers\dashboard\dashboard\dashboard\Api\v1\user;


use MUSICAA\lib\traits\Helper;
use MUSICAA\models\DefaultSettings;
use MUSICAA\models\Devices;
use MUSICAA\models\Languages;
use MUSICAA\models\Login;
use MUSICAA\models\Settings;
use MUSICAA\models\Theme;

class SettingsController extends \MUSICAA\controllers\dashboard\dashboard\dashboard\AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $token = $this->requireAuth();
        $loginId = $token->data->login_id;
        $userId = $token->data->user_id;
        $device = Devices::getByPK($token->data->device_id);

        $set = Settings::getByPK($loginId);
        $def = false;

        if ($set === false)
        {
            $prim = Login::getByUnique($userId);
            if (is_array($prim))
            {
                foreach ($prim as $item)
                {
                    $dev = Devices::getByPK($item->deviceId);
                    if ($dev->is_primary === 'y')
                    {
                        $prim = Login::getByCol('deviceId',$dev->id)[0];
                        break;
                    }
                }

                $set = Settings::getByPK($prim->id);
                if($set === false)
                {
                    goto DefaultThings;
                }

            }else{

                DefaultThings:

                $set = DefaultSettings::getByPK($device->OS);
                $def = true;

            }
        }
        $this->decodeSettings($set);

        unset($set->os,$set->loginId);
        $this->jsonRender(['data' => ['settings' => $set,'default' => $def]],$this->language);
    }

    public function changeAction()
    {
        $this->_lang->load('api.errors.user');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'user');

        $token = $this->requireAuth();
        $loginId = $token->data->login_id;
        $userId  = $token->data->user_id;

        $set = new Settings();
        $set->loginId = $loginId;
        $settings = Settings::getByPK($loginId);

        $mood = $this->filterStr($this->checkInput('post','mood'));
        $theme = Theme::getByPK($mood);
        if ($theme === false)
        {
            $theme = Theme::getByUnique($mood);
            if ($theme === false)
            {
                $this->jsonRender($user_noTheme,$this->language);
            }
        }

        if ($settings !== false && $settings->theme !== $theme->id)
        {
            $this->trackUserData('set.theme',$userId,$settings->theme,$theme->id);
        }
        $set->theme = $theme->id;

        $lang = $this->filterStr($this->checkInput('post','language'));
        $language = Languages::getByPK($lang);
        if ($language === false)
        {
            $language = Languages::getByUnique($lang);
            if ($language === false)
            {
                $this->jsonRender($user_noLanguage,$this->language);
            }
        }
        if ($settings !== false && $settings->language !== $language->id){
            $this->trackUserData('set.language', $userId, $settings->language, $language->id);
        }
        $set->language = $language->id;

        $additional_screen = $this->filterInt($this->checkInput('post','additional_screen'));
        $auto_update = $this->filterInt($this->checkInput('post','auto_update'));

        if ($additional_screen < 0 || $additional_screen > 1 || $auto_update < 0 || $auto_update > 1)
        {
            $this->jsonRender($user_wrongData,$this->language);
        }

        $set->additional_screen = $additional_screen;
        $set->auto_update = $auto_update;

        if ($settings !== false && $settings->additional_screen !== (string)$additional_screen){
            $this->trackUserData('set.add_sec', $userId, $settings->additional_screen, $additional_screen);
        }
        if ($settings !== false && $settings->auto_update !== (string)$auto_update){
            $this->trackUserData('set.auto_upd', $userId, $settings->auto_update, $auto_update);
        }

        // Permissions:
        $run = $this->filterInt($this->checkInput('post','background'));
        $aud = $this->filterInt($this->checkInput('post','audio'));
        $loc = $this->filterInt($this->checkInput('post','location'));

        if ($run < 0 || $run > 1 || $aud < 0 || $aud > 1 || $loc < 0 || $loc > 1)
        {
            $this->jsonRender($user_wrongData,$this->language);
        }

        $per = $this->fromBin($run . $aud . $loc);

        if ($settings !== false && $settings->permissions !== (string)$per){
            $this->trackUserData('set.permissions', $userId, $settings->permissions, $per);
        }
        $set->permissions = $per;

        if ($set->save() !== false)
        {
            $this->decodeSettings($set);

            unset($set->loginId);
            $this->jsonRender(['message' => $user_saveDataSuc,'settings' => $set] , $this->language);
        }

        $this->jsonRender($user_setSaveErr,$this->language);
    }

    public function decodeSettings(&$set)
    {
        foreach ($set as $key=>$value)
        {
            if ($key === 'permissions')
            {
                $data = $this->toBin($set->permissions);
                $set->permissions = [
                    'Running In Background'     =>  (bool) $data[0],
                    'Entry Permit Audio Files'  =>  (bool) $data[1],
                    'Location '                 =>  (bool) $data[2]
                ];
            }else if($key === 'theme'){

                $set->theme = Theme::getByPK($value);

            }else if($key === 'language'){

                $set->language = Languages::getByPK($value);

            }else
            {
                $set->$key = (bool) $value;
            }
        }
    }

}
