<?php


namespace MUSICAA\lib\traits;

use MUSICAA\models\youtube\Channels;
use MUSICAA\models\youtube\Favorite;
use MUSICAA\models\youtube\FavoriteSong;
use MUSICAA\models\youtube\Ids;
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

	    $response = $this->service->search->listSearch('snippet',$queryParams);
        $videos = [];

        foreach ($response->getItems() as $item)
        {
            $video = Video::getByPK($item->id->videoId);
            if ($video === false && isset($item->snippet))
            {
                $video = new Video();
                $video->id = $item->id->videoId;
                $video->name = $item->snippet->title;
                $video->img = $this->getImage($item);

                $videos[] = $video;
            }
        }

        foreach ($videos as $video)
        {
            unset($video->link,$video->playlistId);
        }

        return $videos;
    }

    public function getVideoById($id,$userId)
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

            if ($response->snippet->categoryId === '10'){
                $channel = $this->getChannel($response->snippet->channelId);
                $playlists = $this->getPlaylists($response->snippet->channelId);

                foreach ($playlists as $playlist) {
                    $this->getVideos($playlist->id, $userId, Null, false);
                }

                $video = Video::getByPK($id);

                if ($video === false) {
                    $playlist = Playlists::getMainPlaylist($channel->id);

                    $video = new Video();
                    $video->id = $id;
                    $video->name = $response->snippet->title;
                    $video->playlistId = $playlist->id;
                    $video->img = $this->getImage($response);
                    $video->link = $this->getVideoLink($id);

                    if ($video->save('upd') === false) {
                        $undownloadable = new Undownloadable();
                        $undownloadable->id = $id;
                        $undownloadable->name = $response->snippet->title;
                        $undownloadable->playlistId = $playlist->id;
                        $undownloadable->img = $this->getImage($response);
                        $undownloadable->save('upd');

                        $this->jsonRender([], $this->language, $music_vidCantSave);
                    }
                }
            }else{
                return false;
            }
        }
        $video->is_favorite = $this->is_favorite($userId,$video->id);

        return $video;
    }

    public function getVideoCategory($id)
    {
        $queryParams = [
            'id' => $id
        ];

        $response = $this->service->videos->listVideos('snippet,contentDetails,statistics', $queryParams)->getItems()[0];

        return $response->snippet->categoryId;
    }

	public function getHomeVideos($userId,$vpage=1,$cpage=1)
	{
		$videos = [];
		$videos['videos'] = [];
		$videos['channels'] = [];
		$ids = [];
		$ids['videos'] = [];
		$ids['channels'] = [];
		$ids['lastPage']['v'] = $vpage;
		$ids['lastPage']['c'] = $cpage;
		$id = Ids::getByPK($userId);
		if ($id === false)
        {
            $id = new \stdClass();
            $id->videos = [];
            $id->channels = [];

        }else
        {
            $id = json_decode(Ids::getByPK($userId)->ids);
        }

		$climit = 10;
		$vmax = 24;

		if ($vpage>1 || $cpage>1)
		{
			$vpage = ($vpage>1)? $vpage:2;
			$cpage = ($cpage>1)? $cpage:2;

			$ids['videos'] = array_slice($id->videos,0,$vmax*($vpage-1));
			$ids['channels'] = array_slice($id->channels,0,$climit*($cpage-1));
		}


		$favorite = Favorite::getByUnique($userId);
		if ($favorite !== false)
		{
			$songs = FavoriteSong::getByCol('favoriteId',$favorite->id,["*"]);

			if($songs !== false){

				if (is_object($songs))
				{
					$songs = [$songs];
				}

				shuffle($songs);

				if (count($songs)>8)
				{
					$songs = array_slice($songs,0,8);
				}

				$count = count($songs);

				$vlimit = (int)($vmax / $count);

				foreach ($songs as $song)
				{
					$channel = Channels::getByPK(Playlists::getByPK(Video::getByPK($song->videoId)->playlistId)->channelId);
					if (in_array($channel->id, $ids['channels'], false) === false){
						$ids['channels'][] = $channel->id;
						$videos['channels'][] = $channel;
					}

					$count = 1;
					foreach ($this->getRelated($song->videoId) as $item)
					{
						if ($count > $vlimit)
						{
							break;
						}

						if (in_array($item->id, $ids['videos'], false) === false){
							$ids['videos'][] = $item->id;
							$videos['videos'][] = $item;
							$count++;
						}
					}
				}

				if (count($videos['channels']) > $climit)
				{
					$videos['channels'] = array_slice($videos['channels'],0,$climit);
				}

				if (count($videos['channels']) < $climit)
				{
					foreach (Channels::get('SELECT * FROM channels ORDER BY RAND()') as $channel){
						if (count($videos['channels']) > $climit)
						{
							break;
						}
						if (in_array($channel->id, $ids['channels'], false) === false){
							$ids['channels'][] = $channel->id;
							$videos['channels'][] = $channel;
						}
					}
				}
			}
		}else{
			$videos['videos'] = Video::get('SELECT * FROM video ORDER BY RAND() LIMIT 0,24');
			$videos['channels'] = Channels::get('SELECT * FROM channels ORDER BY RAND() LIMIT 0,10');
		}

//		foreach ($videos['videos'] as $video)
//		{
//			if (isset($video->link))
//			{
//				unset($video->link);
//			}
//			if (isset($video->playlistId))
//			{
//				unset($video->playlistId);
//			}
//		}

		$id = new Ids();
		$id->userId = $userId;
		$id->ids = json_encode($ids);

		$id->save('upd');

		$videos['video_count'] = count($videos['videos']);
		$videos['channel_count'] = count($videos['channels']);
		return $videos;
    }
}
