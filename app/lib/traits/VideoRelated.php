<?php


namespace MUSICAA\lib\traits;


use MUSICAA\models\youtube\Channels;
use MUSICAA\models\youtube\Playlists;
use MUSICAA\models\youtube\Undownloadable;
use MUSICAA\models\youtube\Video;
use YouTube\YouTubeDownloader;

trait VideoRelated
{

    public function getVideoLink($id)
    {
        $yt = new YouTubeDownloader();
        $links = $yt->getDownloadLinks("https://www.youtube.com/watch?v=" . $id);

        foreach ($links as $link)
        {
            if ($link['format'] === "m4a, audio")
            {
                unset($yt,$links);
                return $link['url'];
            }
        }

        return null;
    }



    public function getRelated($id)
    {
        $queryParams = [
            'maxResults' => 25,
            'relatedToVideoId' => $id,
            'type' => 'video',
            'videoCategoryId' => '10'
        ];

        $response = $this->service->search->listSearch('snippet', $queryParams);
        $videos = [];

        foreach ($response->getItems() as $item)
        {
            $video = Video::getByPK($item->id->videoId);
            if ($video === false)
            {
                $video = new Video();
                $video->id = $item->id->videoId;
                $video->name = $item->snippet->title;
                $video->img = $this->getImage($item);
            }
            $videos[] = $video;
        }

        foreach ($videos as $i => $video)
        {
            unset($video->link,$video->playlistId);
        }

        return $videos;
    }

    public function getVideoById($id)
    {
        $this->_lang->load('api.errors.music');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'music');

        $video = Video::getByPK($id);

        if ($video === false) {
            $queryParams = [
                'id' => $id,
                'videoCategoryId' => '10'
            ];

            $response = $this->service->videos->listVideos('snippet,contentDetails,statistics', $queryParams)->getItems()[0];

            $channel = $this->getChannel($response->snippet->channelId);
            $playlists = $this->getPlaylists($response->snippet->channelId);

            foreach ($playlists as $playlist) {
                $this->getVideos($playlist->id, Null, false);
            }

            $video = Video::getByPK($id);

            if ($video === false)
            {
                $playlist = Playlists::getMainPlaylist($channel->id);

                $video = new Video();
                $video->id = $id;
                $video->name = $response->snippet->title;
                $video->playlistId = $playlist->id;
                $video->img = $this->getImage($response);
                $video->link = $this->getVideoLink($id);

                if ($video->save('upd') === false)
                {
                    $undownloadable = new Undownloadable();
                            $undownloadable->id = $id;
                            $undownloadable->name = $response->snippet->title;
                            $undownloadable->playlistId = $playlist->id;
                            $undownloadable->img = $this->getImage($response);
                    $undownloadable->save('upd');

                    $this->jsonRender($music_vidCantSave,$this->language);
                }
            }
        }

        return $video;
    }

}