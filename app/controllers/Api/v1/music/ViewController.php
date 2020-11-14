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
//        $token = $this->requireAuth();
        $id = $this->checkInput('post','channelId');

        $channel = $this->getChannel($id);


                $queryParamsvd = [
                    'maxResults' => 50,
                    'playlistId' => 'PLoYRB6C09WUAwEReaDgIe1wmh6W5HPPc9',
                    'pageToken'  => ''
                ];

                        $videos = $this->service->playlistItems->listPlaylistItems('snippet,contentDetails', $queryParamsvd);
                        var_dump($videos);

//                        foreach ($videos as $video)
//                        {
//                            $v = $video->snippet->resourceId;
//                            if($v->kind !== "youtube#video")
//                            {
//                                continue;
//                            }
//
//                            $vid = new Video();
//                            $vid->id = $v->videoId;
//                            $vid->playlistId = $playlist->id;
//                                    $queryParamsv = [
//                                        'id' => $v->videoId
//                                    ];
//
//                                    $response = $this->service->videos->listVideos('snippet', $queryParamsv)->getItems()[0]->snippet;
//                            $vid->name = $response->title;
//                            $vid->img = $response->thumbnails->high->url;
//
//
//                            if ($vid->save() !== false)
//                            {
//                                continue;
//                            }
//                        }

    }

}