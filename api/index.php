<?php
/**
 * This controller handles API requests.
 *
 * @version 29 - Henry Addo 2010-11-09
 *
 */
require_once('Api_Service.php');
class Api {

	public function __construct()
	{
		// Instantiate the API service
		$api_service = new Api_Service();

		// Run the service
		$api_service->run_service();

		// Avoid caching
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

		$resp = '';

		//echo $api_service->get_response_type();
		if ($api_service->get_response_type() == 'jsonp')
		{
			header("Content-type: application/json; charset=utf-8");
			$resp = $_GET['callback'].'('.$api_service->get_response().')';
		}
		else
		{
			header("Content-type: application/json; charset=utf-8");
			$resp =  $api_service->get_response();
		}

		print $resp;

	}
}

new Api;
