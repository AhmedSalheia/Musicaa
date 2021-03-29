<?php


namespace MUSICAA\lib\traits;


use MUSICAA\models\youtube\Channels;
use MUSICAA\models\youtube\Favorite;
use MUSICAA\models\youtube\FavoriteSong;
use MUSICAA\models\youtube\Playlists;
use MUSICAA\models\youtube\Undownloadable;
use MUSICAA\models\youtube\Video;

trait ChannelThings
{
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

            if ($item->kind !== YOUTUBE_CHANNEL)
            {
                return false;
            }

            $channel = new Channels();
            $channel->id = $id;
            $channel->name = $item->snippet->title;
            $channel->img = $this->getImage($item);

            if ($channel->save() === false)
            {
                $this->jsonRender([], $this->language,'Error Saving Channel Details');
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
                if ($playlist->kind !== YOUTUBE_PLAYLIST)
                {
                    continue;
                }

                $plylst = new Playlists();
                $plylst->id = $playlist->id;
                $plylst->channelId = $channelId;
                $plylst->name = $playlist->snippet->title;
                $plylst->img = $this->getImage($playlist);

                if ($plylst->save('upd') === false)
                {
                    $this->jsonRender([], $this->language,'Error Saving Playlist '.$playlist->snippet->title.' Details');
                }

                $playlists[] = $plylst;
            }

            if ($nextPage !== NULL) {
                $playlists = array_merge($playlists, $this->getPlaylists($channelId, $nextPage));
            }

        }

        return $playlists;
    }



    public function getVideos($playlistId,$userId,$pageToken=Null,$showLinks=true)
    {
        $videos = Video::getByUnique($playlistId)?:[];

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
                if ($video->snippet->resourceId->kind !== YOUTUBE_VIDEO ||
                    Video::getByPK($video->snippet->resourceId->videoId) !== false)
                {
                    continue;
                }

                $vid = new Video();
                $vid->id = $video->snippet->resourceId->videoId;
                $vid->playlistId = $playlistId;
                $vid->name = $video->snippet->title;
                $vid->img = $this->getImage($video);
                $vid->link = $this->getVideoLink($video->snippet->resourceId->videoId);

                if ($vid->save('upd') === false)
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
                $videos = array_merge($videos, $this->getVideos($playlistId,$userId,$nextPage));
            }

        }

        if ($showLinks === false)
        {
            $videos = (is_object($videos))? [$videos]:$videos;
            if ($videos !== false)
            {
                foreach ($videos as $vid)
                {
                    $vid->is_favorite = $this->is_favorite($userId,$vid->id);
                    unset($vid->link,$vid->playlistId);
                }
            }
        }

        return $videos;
    }

    public function getImage($resource)
    {
        if (isset($resource->snippet->thumbnails->high->url)) {
            return $resource->snippet->thumbnails->high->url;
        }

        if (isset($resource->snippet->thumbnails->standard->url)) {
            return $resource->snippet->thumbnails->standard->url;
        }

        if (isset($resource->snippet->thumbnails->default->url)) {
            return $resource->snippet->thumbnails->default->url;
        }

        if (isset($resource->snippet->thumbnails->medium->url)) {
            return $resource->snippet->thumbnails->medium->url;
        }

        return Channels::getByPK($resource->snippet->channelId)->img;
    }

    public function is_favorite($userID,$videoID)
    {
        $favorite = Favorite::getByUnique($userID);

        if ($favorite !== false)
        {
            $favoriteSong = FavoriteSong::getByUnique($videoID);

            if ($favoriteSong !== false && is_object($favoriteSong))
            {
                return true;
            }
        }

        return false;
    }
}
