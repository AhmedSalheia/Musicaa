<?php


namespace MUSICAA\controllers\Api\v1\music;


use MUSICAA\lib\traits\Helper;
use MUSICAA\models\youtube\UserPlaylists;
use MUSICAA\models\youtube\UserPlaylistSongs;
use MUSICAA\models\youtube\Video;

class PlaylistController extends \MUSICAA\controllers\AbstractController
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
            $this->jsonRender(['Playlists'=>$userPlaylists],$this->language);
        }

        $this->jsonRender([],$this->language,$music_EmptyPlyData);
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
            $this->jsonRender(['Playlist' => $playlist],$this->language,$music_plylstCreateSuc);
        }

        $this->jsonRender([],$this->language,$music_plylstCreateErr);
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
            $this->jsonRender([],$this->language,$music_songDoesNotExistErr);
        }

        $plylstID = $this->filterStr($this->checkInput('post','playlistId'));
        $playlist = UserPlaylists::getByPK($plylstID);
        if ($playlist === false)
        {
            $this->jsonRender([],$this->language,$music_plylstDontErr);
        }


        $plySong = UserPlaylistSongs::get('SELECT * FROM userplaylistsongs where playlistId="'.$plylstID.'" AND songId="'.$songId.'"');
        if ($plySong === false){

            $plySong = new UserPlaylistSongs();
            $plySong->playlistId = $playlist->id;
            $plySong->songId = $songId;

            if ($plySong->save() !== false) {
                $this->jsonRender([], $this->language,$music_plySongAddSuc,true);
            }

            $this->jsonRender([], $this->language,$music_plySongAddErr);
        }

        $this->jsonRender([], $this->language,$music_plySongAddAlrErr);
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

            $this->jsonRender([$output],$this->language);
        }

        $this->jsonRender([],$this->language,$music_EmptySongData);
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
            $this->jsonRender([],$this->language,$music_songDoesNotExistErr);
        }

        $plylstID = $this->filterStr($this->checkInput('post','playlistId'));
        if (UserPlaylists::getByPK($plylstID) === false)
        {
            $this->jsonRender([],$this->language,$music_plylstDontErr);
        }

        $playSong = UserPlaylistSongs::get('SELECT * FROM userplaylistsongs where playlistId="'.$plylstID.'" AND songId="'.$songId.'"');

        if ($playSong !== false)
        {
            $playSong = $playSong[0];
            if($playSong->delete() !== false)
            {
                $this->jsonRender([], $this->language,$music_plySongDelSuc,true);
            }

            $this->jsonRender([], $this->language,$music_plySongDelErr);
        }
        $this->jsonRender([], $this->language,$music_plySongNoDelErr);
    }
}
