<?php


namespace MUSICAA\controllers\Api\v1\data;


use MUSICAA\lib\traits\Helper;

class ApidataController extends \MUSICAA\controllers\AbstractController
{
	use Helper;

	public function defaultAction()
	{
		$this->jsonRender(['data'=>['api_versions'=>API_VER,'latest_version'=>CURRENT_VER]],$this->language);
	}
}