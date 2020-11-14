<?php


namespace MUSICAA\lib\traits;


use MUSICAA\models\youtube\Channels;
use MUSICAA\models\youtube\Playlists;
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
//                var_dump($link);;
                return $link['url'];
            }
        }

        return null;
    }


    public function getChannel($id)
    {

        $channel = Channels::getByPK($id);

        if ($channel === false)
        {

            $queryParams = [
                'id' => $id
            ];

            $response = $this->service->channels->listChannels('snippet,contentDetails,statistics', $queryParams);
            $item = $response->getItems()[0];

            if ($item->kind !== "youtube#channel")
            {
                return false;
            }

            $channel = new Channels();
            $channel->id = $id;
            $channel->name = $item->snippet->title;
            $channel->img = $item->snippet->thumbnails->high->url;

            if ($channel->save() === false)
            {
                $this->jsonRender('Error Saving Channel Details', $this->language);
            }

        }

        return $channel;
    }


    public function getPlaylists($channelId,$pageToken=Null)
    {
        $playlists = Playlists::getByUnique($channelId);

        if ($playlists === false || $pageToken !== Null)
        {

            $queryParams = [
                'channelId' => $channelId,
                'maxResults' => 50,
                'pageToken' => ($pageToken !== Null)? $pageToken:''
            ];

            $response = $this->service->playlists->listPlaylists('snippet,contentDetails', $queryParams);
            $nextPage = $response->getNextPageToken();
            $playlists = [];

            foreach ($response->getItems() as $playlist)
            {
                if ($playlist->kind !== "youtube#playlist")
                {
                    continue;
                }

                $plylst = new Playlists();
                $plylst->id = $playlist->id;
                $plylst->channelId = $channelId;
                $plylst->name = $playlist->snippet->title;
                $plylst->img = $playlist->snippet->thumbnails->high->url;

                if ($plylst->save('upd') === false)
                {
                    $this->jsonRender('Error Saving Playlist '.$playlist->id.' Details', $this->language);
                }

                $playlists[] = $plylst;
            }

            if ($nextPage !== NULL) {
                $playlists = array_merge($playlists, $this->getPlaylists($channelId, $nextPage));
            }

        }

        return $playlists;
    }



    public function getVideos($playlistId,$pageToken=Null)
    {
        $videos = Video::getByUnique($playlistId);
        var_dump($videos);
//        exit();
        $queryParams = [
            'maxResults' => 50,
            'playlistId' => $playlistId,
            'pageToken' => ($pageToken !== Null)? $pageToken:''
        ];

        $response = $this->service->playlistItems->listPlaylistItems('snippet,contentDetails', $queryParams);
        $items = $response->getPageInfo()->totalResults;

        if ($videos === false || $pageToken !== Null || count($videos) !== $items)
        {

            $nextPage = $response->getNextPageToken();
            $videos = [];

            foreach ($response->getItems() as $video)
            {
                if ($video->snippet->resourceId->kind !== "youtube#video" ||
                    strtolower($video->snippet->title) === 'private video' ||
                    Video::getByPK($video->snippet->resourceId->videoId) !== false)
                {
                    continue;
                }

                $vid = new Video();
                $vid->id = $video->snippet->resourceId->videoId;
                $vid->playlistId = $playlistId;
                $vid->name = $video->snippet->title;
                $vid->img = $video->snippet->thumbnails->high->url;
                $vid->link = $this->getVideoLink($video->snippet->resourceId->videoId);

                if ($vid->save('upd') === false)
                {
                    var_dump($vid,$video);
                    $this->jsonRender('Error Saving Video ['.$video->snippet->resourceId->videoId.'] Details', $this->language);
                }
                $videos[] = $vid;
            }

            if ($nextPage !== NULL) {
                $videos = array_merge($videos, $this->getVideos($playlistId, $nextPage));
            }

        }

        return $videos;
    }

}