<?php


namespace MUSICAA\controllers\Api\v1\music;

use MUSICAA\controllers\AbstractController;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\youtube\Channels;
use MUSICAA\models\youtube\Playlists;
use MUSICAA\models\youtube\Video;

class ViewController extends AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->jsonRender('No Action Selected',$this->language);
    }

    public function channelAction()
    {
        $token = $this->requireAuth();
        $id = $this->checkInput('post','channelId');

        $channel = $this->getChannel($id);
        $playlists = $this->getPlaylists($id);
        $videos = [];

        foreach ($playlists as $playlist)
        {
            $videos[] = [$playlist->name => $this->getVideos($playlist->id)];
        }

        $this->jsonRender(['data'=>$videos],$this->language);
    }

    public function videoAction()
    {
        $token = $this->requireAuth();
        $id = $this->checkInput('post','videoId');

        $video = Video::getByPK($id);
        $playlist = Playlists::getByPK($video->playlistId);
        $channel = Channels::getByPK($playlist->channelId);


        $handle = curl_init($video->link);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($handle, CURLOPT_TIMEOUT,10);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        var_dump($video->link,$httpCode);
        $output = [

        ];

//        var_dump($channel);
    }

}