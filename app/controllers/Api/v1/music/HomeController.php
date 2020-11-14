<?php


namespace MUSICAA\controllers\Api\v1\music;


use MUSICAA\lib\traits\Helper;
use MUSICAA\models\youtube\Video;
use YouTube\YouTubeDownloader;

class HomeController extends \MUSICAA\controllers\AbstractController
{
    use Helper;

    public function defaultAction()
    {

        $client = new \Google_Client();
        $client->setApplicationName('API code samples');
        $client->setDeveloperKey('AIzaSyC27cQuXdJQ9Xj72Usu-OOP1R-eAGNuGfM');

// Define service object for making API requests.
        $service = new \Google_Service_YouTube($client);

        $queryParams = [
            'chart' => 'mostPopular',
            'videoCategoryId' => '10',
            'pageToken' => 'CAUQAA'
        ];

        $response = $service->videos->listVideos('snippet,contentDetails,statistics', $queryParams);
        $items = $response->getItems();

        foreach ($items as $item) {
            $video = Video::getByPK($item->id);

            if ($video === false)
            {
                $video = new Video();
                $video->id = $item->id;

                $yt = new YouTubeDownloader();
                $links = $yt->getDownloadLinks("https://www.youtube.com/watch?v=" . $item->id);

                foreach ($links as $link)
                {
                    if ($link['format'] === "m4a, audio")
                    {
                        $video->link = $link['url'];
                        break;
                    }
                }

                $video->save();
            }
        }
    }
}