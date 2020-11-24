<?php


namespace MUSICAA\controllers\Api\v1\music;

use MUSICAA\controllers\AbstractController;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\youtube\Channels;
use MUSICAA\models\youtube\Playlists;

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
        $userID = $token->data->user_id;

        $id = $this->checkInput('post','channelId');

        $playlists = $this->getPlaylists($id);
        $videos = [];

        foreach ($playlists as $playlist)
        {
            $videos[] = [$playlist->name => $this->getVideos($playlist->id,$userID,Null,false)];
        }

        $this->jsonRender(['data'=>$videos],$this->language);
    }

    public function videoAction()
    {
        $token = $this->requireAuth();
        $userID = $token->data->user_id;

        $id = $this->checkInput('post','videoId');

        $video = $this->getVideoById($id,$userID);

        $playlist = Playlists::getByPK($video->playlistId);
        $channel = Channels::getByPK($playlist->channelId);

        if ($this->getHttpCode($video->link) >= 400){
            $video->link = $this->getVideoLink($video->id);
            $video->save('upd');
        }

        $output = [
            'video' => [
                'id' => $video->id,
                'name' => $video->name,
                'link' => $video->link,
                'is_favorite' => $video->is_favorite,
                'playlist' => [
                    'id'   => $playlist->id,
                    'name' => $playlist->name,
                    'img'  => $playlist->img,

                    'channel' => [
                        'id'    => $channel->id,
                        'name'  => $channel->name,
                        'img'   => $channel->img
                    ]
                ]
            ],
            'related' => [
                $this->getRelated($video->id)
            ]
        ];

        $this->jsonRender($output,$this->language);

    }

}