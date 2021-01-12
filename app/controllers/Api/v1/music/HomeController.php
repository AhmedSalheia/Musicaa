<?php


namespace MUSICAA\controllers\Api\v1\music;


use MUSICAA\lib\traits\Helper;
use MUSICAA\models\youtube\Undownloadable;
use YouTube\YouTubeDownloader;

class HomeController extends \MUSICAA\controllers\AbstractController
{
    use Helper;

    public function defaultAction()
    {
	    $token = $this->requireAuth();

	    $vpage = 1;
	    $cpage = 1;

	    if (isset($_POST['vpage']))
	    {
	    	$vpage = $this->filterInt($this->checkInput('post','vpage'));
	    }

	    if (isset($_POST['cpage']))
	    {
		    $cpage = $this->filterInt($this->checkInput('post','cpage'));
	    }

		$response = $this->getHomeVideos($token->data->user_id,$vpage,$cpage);

        $this->jsonRender(['data'=>$response],$this->language);
    }
}
