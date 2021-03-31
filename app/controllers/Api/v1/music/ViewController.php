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
        $this->jsonRender([],$this->language,'No Action Selected');
    }

    public function channelAction()
    {
        $token = $this->requireAuth();
        $userID = $token->data->user_id;

        $id = $this->checkInput('post','channelId');

        $this->track($userID,'view.channel',$id);
        $channel = $this->getChannel($id);

        $playlists = $this->getPlaylists($channel->id);
        $videos = [];
        $vcount = 0;

        foreach ($playlists as $playlist)
        {
            $vid = $this->getVideos($playlist->id,$userID,Null,false);
            $vcount += count($vid);
            $videos[] = [$playlist->name => $vid];
        }

        $channel->playlistCount = count($playlists);
        $channel->videosCount = $vcount;

        $this->jsonRender(['channel'=>$channel,'videos'=>$videos],$this->language);
    }

    public function videoAction()
    {
        $this->_lang->load('api.errors.music');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'music');

        $token = $this->requireAuth();
        $userID = $token->data->user_id;

        $id = $this->checkInput('post','videoId');

        $this->track($userID,'view.video',$id);
        $video = $this->getVideoById($id,$userID);
        if ($video === false)
        {
            $this->jsonRender([],$this->language,$music_notSong);
        }

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
