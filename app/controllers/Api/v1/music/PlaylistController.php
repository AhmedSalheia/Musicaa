<?php


namespace MUSICAA\controllers\dashboard\dashboard\dashboard\Api\v1\music;


use MUSICAA\lib\traits\Helper;
use MUSICAA\models\youtube\UserPlaylists;
use MUSICAA\models\youtube\UserPlaylistSongs;
use MUSICAA\models\youtube\Video;

class PlaylistController extends \MUSICAA\controllers\dashboard\dashboard\dashboard\AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $this->_lang->load('api.errors.music');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'music');

        $token = $this->requireAuth();

        $userPlaylists = UserPlaylists::getByCol('userId',$token->data->user_id);

        if ($userPlaylists !== false)
        {
            $this->jsonRender(['data'=>$userPlaylists],$this->language);
        }

        $this->jsonRender($music_EmptyPlyData,$this->language);
    }

    public function createAction()
    {
        $this->_lang->load('api.errors.music');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'music');

        $token = $this->requireAuth();
        $user = $token->data->user_id;

        $name = $this->filterStr($this->checkInput('post','playlistName'));

        $playlist = new UserPlaylists();
        $playlist->id = $this->randText(15);
        $playlist->name = $name;
        $playlist->userId = $user;

        if ($playlist->save() !== false)
        {
            unset($playlist->userId);
            $this->jsonRender(['data' => $playlist,'message'=>$music_plylstCreateSuc],$this->language);
        }

        $this->jsonRender($music_plylstCreateErr,$this->language);
    }

    public function addAction()
    {
        $this->_lang->load('api.errors.music');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'music');

        $token = $this->requireAuth();
        $user = $token->data->user_id;

        $songId = $this->filterStr($this->checkInput('post','songId'));
        if (Video::getByPK($songId) === false)
        {
            $this->jsonRender($music_songDoesNotExistErr,$this->language);
        }

        $plylstID = $this->filterStr($this->checkInput('post','playlistId'));
        $playlist = UserPlaylists::getByPK($plylstID);
        if ($playlist === false)
        {
            $this->jsonRender($music_plylstDontErr,$this->language);
        }


        $plySong = UserPlaylistSongs::get('SELECT * FROM userplaylistsongs where playlistId="'.$plylstID.'" AND songId="'.$songId.'"');
        if ($plySong === false){

            $plySong = new UserPlaylistSongs();
            $plySong->playlistId = $playlist->id;
            $plySong->songId = $songId;

            if ($plySong->save() !== false) {
                $this->jsonRender(['message' => $music_plySongAddSuc], $this->language);
            }

            $this->jsonRender($music_plySongAddErr, $this->language);
        }

        $this->jsonRender($music_plySongAddAlrErr, $this->language);
    }

    public function viewAction()
    {
        $this->_lang->load('api.errors.music');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'music');

        $token = $this->requireAuth();

        $id = $this->filterStr($this->checkInput('post','playlistId'));
        $playSongs = UserPlaylistSongs::getByCol('playlistId',$id);
        $output = [];

        if ($playSongs !== false)
        {
            foreach ($playSongs as $playSong)
            {
                $song = Video::getByPK($playSong->songId);
                unset($song->link,$song->playlistId);
                $output[] = $song;
            }

            $this->jsonRender(['data'=>$output],$this->language);
        }

        $this->jsonRender(['massage' => $music_EmptySongData],$this->language);
    }

    public function deleteAction()
    {
        $this->_lang->load('api.errors.music');
        extract($this->_lang->get(),EXTR_PREFIX_ALL,'music');

        $token = $this->requireAuth();
        $user = $token->data->user_id;

        $songId = $this->filterStr($this->checkInput('post','songId'));
        if (Video::getByPK($songId) === false)
        {
            $this->jsonRender($music_songDoesNotExistErr,$this->language);
        }

        $plylstID = $this->filterStr($this->checkInput('post','playlistId'));
        if (UserPlaylists::getByPK($plylstID) === false)
        {
            $this->jsonRender($music_plylstDontErr,$this->language);
        }

        $playSong = UserPlaylistSongs::get('SELECT * FROM userplaylistsongs where playlistId="'.$plylstID.'" AND songId="'.$songId.'"');

        if ($playSong !== false)
        {
            $playSong = $playSong[0];
            if($playSong->delete() !== false)
            {
                $this->jsonRender(['massage' => $music_plySongDelSuc], $this->language);
            }

            $this->jsonRender($music_plySongDelErr, $this->language);
        }
        $this->jsonRender($music_plySongNoDelErr, $this->language);
    }
}
