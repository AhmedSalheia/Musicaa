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
        $playlists = (is_object($playlists))? [$playlists]:$playlists;
        $queryParams = [
            'channelId' => $channelId,
            'maxResults' => 50,
            'pageToken' => ($pageToken !== Null)? $pageToken:''
        ];

        $response = $this->service->playlists->listPlaylists('snippet,contentDetails', $queryParams);
        $items = $response->getPageInfo()->totalResults;

        if ($playlists === false || $pageToken !== Null || $items !== count($playlists))
        {
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
        $undownload = Undownloadable::getByUnique($playlistId);
        $undownload = ($undownload === false)? []:$undownload;
        $undownload = (is_object($undownload))? [$undownload]:$undownload;

        $queryParams = [
            'maxResults' => 50,
            'playlistId' => $playlistId,
            'pageToken' => ($pageToken !== Null)? $pageToken:''
        ];

        $response = $this->service->playlistItems->listPlaylistItems('snippet,contentDetails', $queryParams);
        $items = $response->getPageInfo()->totalResults;

        if ($videos === false || $pageToken !== Null || is_object($videos) || count(array_merge($undownload,$videos)) !== $items)
        {
            $nextPage = $response->getNextPageToken();

            foreach ($response->getItems() as $video)
            {
                if ($video->snippet->resourceId->kind !== "youtube#video" ||
                    Video::getByPK($video->snippet->resourceId->videoId) !== false)
                {
                    continue;
                }

                $vid = new Video();
                $vid->id = $video->snippet->resourceId->videoId;
                $vid->playlistId = $playlistId;
                $vid->name = $video->snippet->title;
                @$vid->img = $video->snippet->thumbnails->high->url;
                $vid->link = $this->getVideoLink($video->snippet->resourceId->videoId);

                $save = $vid->save('upd');
                if ($save === false)
                {
                    $undown = new Undownloadable();
                    $undown->id = $vid->id;
                    $undown->playlistId = $vid->playlistId;
                    $undown->name = $vid->name;
                    $undown->img = (strtolower($vid->name) !== 'private video')?$vid->img:'NotFound';

                    $undown->save('upd');
                    continue;
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