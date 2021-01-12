<?php


namespace MUSICAA\controllers\dashboard\dashboard\dashboard\Api\v1\music;


use MUSICAA\lib\traits\Helper;

class SearchController extends \MUSICAA\controllers\dashboard\dashboard\dashboard\AbstractController
{
    use Helper;

    public function defaultAction()
    {
        $token = $this->requireAuth();
        $userID = $token->data->user_id;

        $q = implode(",",explode(" ",$this->filterStr($this->checkInput("post","q"))));
        $nextPage = '';

        $this->track($userID,'search',$q);

        if (isset($_POST['nextPage']) && $_POST['nextPage'] !== '')
        {
            $nextPage = $this->filterStr($this->checkInput('post','nextPage'));
        }

        $queryParams = [
            'maxResults' => 21,
            'q' => $q,
            'pageToken' => $nextPage
        ];

        $searchResults = $this->service->search->listSearch('snippet', $queryParams);
        $nextPageToken = $searchResults->getNextPageToken();

        $results = [];

        foreach ($searchResults->getItems() as $search)
        {
            $res = new \stdClass();
            $kind = $search->id->kind;

            if ($kind === YOUTUBE_CHANNEL)
            {
                $res->id = $search->id->channelId;
                $res->type = 'channel';
            }elseif ($kind === YOUTUBE_PLAYLIST)
            {
                $res->id = $search->id->playlistId;
                $res->type = 'playlist';
            }else
            {
                $res->id = $search->id->videoId;
                $res->type = 'audio';
            }

            $res->name = $search->snippet->title;
            $res->img = $this->getImage($search);

            $results[] = $res;
        }

        $this->jsonRender(['results'=>$results,'nextPage'=>$nextPageToken],$this->language);
    }
}
