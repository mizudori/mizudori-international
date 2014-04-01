<?php
/**
 * This controller handles API requests.
 *
 * @version 29 - Henry Addo 2010-11-09
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 *
 * Api_Controller
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @subpackage Controllers
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
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