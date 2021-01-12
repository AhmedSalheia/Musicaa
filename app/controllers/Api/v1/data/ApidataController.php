<?php


namespace MUSICAA\controllers\dashboard\dashboard\dashboard\Api\v1\data;


use MUSICAA\lib\traits\Helper;

class ApidataController extends \MUSICAA\controllers\dashboard\dashboard\dashboard\AbstractController
{
	use Helper;

	public function defaultAction()
	{
		$this->jsonRender(['data'=>['api_versions'=>API_VER,'latest_version'=>CURRENT_VER]],$this->language);
	}
}
