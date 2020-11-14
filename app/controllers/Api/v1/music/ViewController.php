<?php


namespace MUSICAA\controllers\Api\v1\music;


use MUSICAA\controllers\AbstractController;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\youtube\Channels;
use MUSICAA\models\youtube\Playlists;
use MUSICAA\models\youtube\Video;
use YouTube\YouTubeDownloader;

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

        var_dump($videos);
    }

    public function videoAction()
    {
        $id = $this->checkInput('get','id');

        var_dump($this->getVideoLink($id));
    }

}