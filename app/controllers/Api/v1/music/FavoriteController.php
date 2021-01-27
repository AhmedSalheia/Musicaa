<?php


namespace MUSICAA\controllers\Api\v1\music;


use MUSICAA\controllers\AbstractController;
use MUSICAA\lib\traits\Helper;
use MUSICAA\models\youtube\Channels;
use MUSICAA\models\youtube\Favorite;
use MUSICAA\models\youtube\FavoriteSong;
use MUSICAA\models\youtube\Playlists;
use MUSICAA\models\youtube\Video;

class FavoriteController extends AbstractController
{
    use Helper;
    public function defaultAction()
    {
        $this->_lang->load('api.errors.music');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'music');

        $token = $this->requireAuth();
        $userID = $token->data->user_id;

        $favorite = Favorite::getByUnique($userID);

        if ($favorite === false)
        {
            $this->jsonRender([],$this->language,$music_NoFavorite);
        }

        $favSongs = FavoriteSong::getByCol('favoriteId',$favorite->id);
        if ($favSongs === false)
        {
            $this->jsonRender([],$this->language,$music_NoFavorite);
        }

        $videos = [];
        foreach ($favSongs as $favSong)
        {
            $vid = Video::getByPK($favSong->videoId);
            unset($vid->link,$vid->playlistId);
            $videos[] = $vid;
        }

        $this->track($userID,'Favorite.show',count($videos));

        $this->jsonRender([$videos],$this->language);
    }

    public function addAction()
    {
        $this->_lang->load('api.errors.music');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'music');

        $token = $this->requireAuth();
        $userID = $token->data->user_id;

        $type = $this->_params[0] ?? '';

        switch ($type)
        {
            case 'video':
            default:
                $id = $this->filterStr($this->checkInput('post','videoId'));

                $this->track($userID,'Favorite.add.vid',$id);
                if (Video::getByPK($id) !== false)
                {
                    $favorite = Favorite::getByUnique($userID);

                    if ($favorite === false)
                    {
                        $favorite = new Favorite();
                        $favorite->userId = $userID;

                        if ($favorite->save() !== false)
                        {
                            $err = $music_FavAddErr;
                            goto printErr;
                        }
                    }

                    $favoriteSong = new FavoriteSong();
                    $favoriteSong->favoriteId = $favorite->id;
                    $favoriteSong->videoId = $id;

                    $save = $favoriteSong->save();

                    if (!is_object($save) && $save !== false)
                    {
                        $this->jsonRender([],$this->language,$music_FavAddSuc,true);

                    }elseif(is_object($save) && $save !== false){

                        if($save->delete() === true)
                        {
                            $this->jsonRender([],$this->language,$music_FavRemSuc,true);
                        }else
                        {
                            $err = $music_FavRemErr;
                            goto printErr;
                        }

                    }else
                    {
                        $err = $music_FavAddErr;
                        printErr:
                        $this->jsonRender([],$this->language,$err);
                    }

                }else
                {
                    $this->jsonRender([],$this->language,$music_FavAddErr);
                }

                break;

            case 'playlist':

                $id = $this->filterStr($this->checkInput('post','playlistId'));

                $this->track($userID,'Favorite.add.play',$id);

                $playlist = Playlists::getByPK($id);

                if ($playlist !== false)
                {
                    $favorite = Favorite::getByUnique($userID);

                    if ($favorite === false)
                    {
                        $favorite = new Favorite();
                        $favorite->userId = $userID;

                        if ($favorite->save() !== false)
                        {
                            $err = $music_FavAddErr;
                            goto printErr;
                        }
                    }

                    $videos = Video::getByUnique($playlist->id);
                    $videos = (is_object($videos))? [$videos]:$videos;

	                if ($videos !== false) {

		                foreach ($videos as $video) {
			                $favSong = new FavoriteSong();
			                $favSong->favoriteId = $favorite->id;
			                $favSong->videoId = $video->id;

			                if ($favSong->save('upd') === false) {
				                $err = $music_FavAddErr;
				                goto printErr;
			                }
		                }
	                }

	                $this->jsonRender([], $this->language,$music_FavAddSuc, true);

                }else{
                    $err = $music_FavAddErr;
                    goto printErr;
                }

                break;
        }
    }
}
