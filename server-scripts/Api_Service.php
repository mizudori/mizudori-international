<?php
/**
 * Api_Service
 *
 * This class runs the API service. It abstracts the details of handling of the API
 * requests from the API controller. All task switching and routing is handled by
 * this class.
 *
 * The API routing works through inversion of control (IoC). The name of the library
 * that services the api request is inferred from the name of the task. Not all API
 * requests have their own libraries. As such, this service makes use of a
 * "task routing table". This table is a key=>value array with the key being the
 * name of the api task and the value the name of the implementing library
 * or associative array of the class name and callback method to service the request
 */

// Suffix for all API library names
define('API_LIBRARY_SUFFIX', '_Api_Object');
define('ROOT_DIR', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

final class Api_Service {

	/**
	 * The API request parameters
	 * @var array
	 */
	private $request = array();

	/**
	 * Response to be returned to the calling controller
	 * @var string
	 */
	private $response;

	/**
	 * Format in which the response is returned to the client - defaults to JSON
	 * @var string
	 */
	private $response_type;

	/**
	 * API library object to handle the requested task
	 * @var Api_Object
	 */
	private $api_object;

	/**
	 * Name of the API task to be routed
	 * @var string
	 */
	private $task_name;

	/**
	 * IP Address of the client making the API request
	 * @var string
	 */
	private $request_ip_address;

	/**
	 * API request parameters
	 * @var array
	 */
	private $api_parameters;

	public function __construct()
	{
		// Set the request data
		$this->request = ($_SERVER['REQUEST_METHOD'] == 'POST')
			? $_POST
			: $_GET;

		// Check if the IP is from a shared internet connection
		if ( ! empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$this->request_ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		// Check if the IP address is passed from a proxy server such as Nginx
		elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$this->request_ip_address  = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$this->request_ip_address = $_SERVER['REMOTE_ADDR'];
		}
	}

	/**
	 * Runs the API service
	 */
	public function run_service()
	{
		// Route the API task
		$this->_route_api_task();
	}

	/**
	 * Gets the API request parameters as an array
	 *
	 * @return array
	 */
	public function get_request()
	{
		return $this->request;
	}

	/**
	 * Sets the response type
	 *
	 * @param $response_type New value for $this->response_type
	 */
	public function set_response_type($response_type)
	{
		$this->response_type = $response_type;
	}

	/**
	 * Returns the response type
	 *
	 * @return string
	 */
	public function get_response_type()
	{
		return $this->response_type;
	}

	/**
	 * Sets the response data
	 *
	 * @param mixed $response_data
	 */
	public function set_response($response_data)
	{
		$this->response = (is_array($response_data))
			? $this->array2json($response_data)
			: $response_data;
	}

	/**
	 * Gets the response data
	 *
	 * @return string The response to the API request
	 */
	public function get_response()
	{
		return $this->response;
	}

	/**
	 * Gets the name of the task being handled by the API service
	 *
	 * @return string
	 */
	public function get_task_name()
	{
		return $this->task_name;
	}

	/**
	 * Initializes the API library to be used to service the API task
	 *
	 * The name of API library file containing the class implementation is
	 * constructed/inferred from the @param $class_name
	 *
	 * @param string $base_name Name of the implementing class
	 */
	private function _init_api_library($base_name)
	{
		// Generate the name of the class
		$class_name = $base_name.API_LIBRARY_SUFFIX;

		// Check if the implementing library exists
		if ( ! file($class_name.'.php'))
		{
			throw new Exception('File not found',$class_name.'.php');
		}

		// Include the implementing API library file
		require_once $class_name.'.php';

		// Temporary instance for type checking
		$temp_api_object = new $class_name($this);

		// Check if the implementing library is an instance of Api_Object
		// NOTE: All API libraries *MUST* be subclasses of Api_Object
		if ( ! $temp_api_object instanceof Api_Object)
			throw new Exception('Invalid Api library', $class_name, 'Api_Object');

		// Discard the old copy
		unset($this->temp_api_object);

		// Instaniate a fresh copy of the API library
		$this->api_object = new $class_name($this);

		//print_r(get_class_methods($this->api_object));exit;

	}

	/**
	 * Makes sure the appropriate key is there in a given
	 * array (POST or GET) and that it is set
	 *
	 * @param arrray $arr - The given array.
	 * @param string $index  The array key index to lookup
	 * @return bool
	 */
	public function verify_array_index(array & $arr, $index)
	{
		return (isset($arr[$index]) AND array_key_exists($index, $arr));
	}

	/**
	 * Routes the API task requests to their respective API libraries
	 *
	 * The name of the library is inferred from the name of the task. If the
	 * library is not found, a lookup is done in the task routing table. If the
	 * lookup fails, the API task request returns a "not found"(404) error
	 */
	private function _route_api_task()
	{

		// Make sure we have a task to work with
		if ( ! $this->verify_array_index($this->request, 'task'))
		{
			$this->set_response($this->get_error_msg(001, 'task'));

			return;
		}
		else
		{
			$this->task_name = ucfirst($this->request['task']);
		}

		// Load the base API library
		require_once('Api_Object.php');

		// Get the task handler (from the api config file) for the requested task
		$task_handler = $this->_get_task_handler(strtolower($this->task_name));

		$task_library_found = FALSE;

		// Check if handler has been set
		if (isset($task_handler))
		{
			// Check if the handler is an array
			if (is_array($task_handler))
			{

				// Load the library for the specified class
				$this->_init_api_library($task_handler[0]);

				// Execute the callback function
				call_user_func(array($this->api_object, $task_handler[1]));
			}
			else
			{

				// Load the library specified in $task_handler
				$this->_init_api_library($task_handler);

				// Perform the requested task
				$this->api_object->perform_task();
			}

			// Set the response data
			$this->response = $this->api_object->get_response_data();

			$task_library_found = TRUE;
		}
		else // Task handler not found in routing table therefore look the implementing library
		{
			// All library file names *must* be suffixed with the value specified in API_LIBRARY_SUFFIX
			$library_file_name = $this->task_name.API_LIBRARY_SUFFIX;

			if ( file($library_file_name)) // Library file exists
			{
				// Initialize the API library
				$this->_init_api_library($this->task_name);

				// Perform the requested task
				$this->api_object->perform_task();

				// Set the response data
				$this->response = $this->api_object->get_response_data();

				$task_library_found = TRUE;
			}
			else
			{   // Library not found
				$this->response = $this->array2json(array(
					"error" => $this->get_error_msg(999)
				));

				return;
			}
		}

		// Discard the API object from memory
		if (isset($this->api_object))
		{
			unset($this->api_object);
		}
	}

	/**
	 * Looks up the api config file for the library that handles the task
	 * specified in @param $task. The api config file is the API task routing
	 * table
	 *
	 * @param string $task - Task to be looked up in the routing table
	 * @return mixed
	 */
	private function _get_task_handler($task)
	{

		$t = $this->_api_router();

		$task_handler = $t[$task];

		return (isset($task_handler))
			? $task_handler
			: NULL;
	}


	/**
	 * Displays Error codes with their corresponding messages.
	 * returns an array error - array("code" => "CODE",
	 * "message" => "MESSAGE") based on the given code
	 *
	 * @param string $errcode  - The error code to be displayed.
	 * @param string $param - The missing parameter.
	 * @param string $message - The error message to be displayed.
	 * @return array
	 */
	public function get_error_msg($errcode, $param = '', $message='')
	{
		switch ($errcode)
		{
			case 0:
				return array(
					"code" => "0",
					"message" => "No error"
				);

			case 001:
				return array(
					"code" => "001",
					"message" => "missing - $param."
				);

			case 002:
				return array(
					"code" => "002",
					"message" => "Invalid parameter"
				);

			case 003:
				return array("code" => "003", "message" => $message);

			case 004:
				return array(
					"code" => "004",
					"message" =>"Method not supported"
				);

			case 005:
				return array(
					"code" => "005",
					"message" => "No data"
				);

			default:
				return array(
					"code" => "999",
					"message" => "Resources not found"
				);
		}
	}

	private function _api_router()
	{
		$config = array(
			// Version
			"order" => "Order_Forms",
			"contact" => "Contact_Forms"
		);

		return $config;
	}

	/**
	 * PHP 5.1.x doesn't seem to support json_encode.
	 * Copied this manual implementation from
	 * http://de.php.net/json_encode#100835
	 * @param  [type] $arr [description]
	 * @return [type]      [description]
	 */
	public function array2json($data) {
		if(function_exists('json_encode')) return json_encode($data); //Lastest versions of PHP already has this functionality.
		if( is_array($data) || is_object($data) ) {
		$islist = is_array($data) && ( empty($data) || array_keys($data) === range(0,count($data)-1) );

		if( $islist ) {
			$json = '[' . implode(',', array_map('array2json', $data) ) . ']';
		} else {
			$items = Array();
			foreach( $data as $key => $value ) {
				$items[] = $this->array2json("$key") . ':' . $this->array2json($value);
			}
			$json = '{' . implode(',', $items) . '}';
		}
	} elseif( is_string($data) ) {
		# Escape non-printable or Non-ASCII characters.
		# I also put the \\ character first, as suggested in comments on the 'addclashes' page.
		$string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
		$json    = '';
		$len    = strlen($string);
		# Convert UTF-8 to Hexadecimal Codepoints.
		for( $i = 0; $i < $len; $i++ ) {

			$char = $string[$i];
			$c1 = ord($char);

			# Single byte;
			if( $c1 <128 ) {
				$json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);
				continue;
			}

			# Double byte
			$c2 = ord($string[++$i]);
			if ( ($c1 & 32) === 0 ) {
				$json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);
				continue;
			}

			# Triple
			$c3 = ord($string[++$i]);
			if( ($c1 & 16) === 0 ) {
				$json .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128));
				continue;
			}

			# Quadruple
			$c4 = ord($string[++$i]);
			if( ($c1 & 8 ) === 0 ) {
				$u = (($c1 & 15) << 2) + (($c2>>4) & 3) - 1;

				$w1 = (54<<10) + ($u<<6) + (($c2 & 15) << 2) + (($c3>>4) & 3);
				$w2 = (55<<10) + (($c3 & 15)<<6) + ($c4-128);
				$json .= sprintf("\\u%04x\\u%04x", $w1, $w2);
			}
		}
	} else {
		# int, floats, bools, null
		$json = strtolower(var_export( $data, true ));
	}
	return $json;
	}

}
