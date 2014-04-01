<?php
/**
 * Api
 *
 * Base abstract class for all API library implementations.
 *
 * This provides a rudimentary api for sending a POST data to Google
 * docs forms and for emailing data to
 */
abstract class Api_Object {

	/**
	 * Form validation error messages
	 * @var array
	 */
	protected $messages = array();

	/**
	 * API validation error message
	 * @var mixed
	 */
	protected $error_messages = '';

	/**
	 * Domain name of the URL accessing the API
	 * @var string
	 */
	protected $domain;

	/**
	 * HTTP POST and/or GET data submitted via the API
	 * @var array
	 */
	protected $request = array();

	/**
	 * Format in which the data is to be returned to the client - defaults to JSON
	 * if none has been specified
	 * @var string
	 */
	protected $response_type;

	/**
	 * Response data to be returned to the client
	 * @var string
	 */
	protected $response_data;

	/**
	 * Api_Service object
	 * @var Api_Service
	 */
	protected $api_service;

	public function __construct($api_service)
	{
		$this->api_service = $api_service;

		if ( ! is_null($api_service->get_response_type()))
		{
			$this->request = $api_service->get_request();
			$this->response_type = $api_service->get_response_type();
		}
		else
		{
			$this->set_request($api_service->get_request());
		}
	}

	/**
	 * Sets the request and determines the format in which the request data is
	 * to be returned to the client
	 */
	public function set_request($request)
	{
		$this->request = $request;

		// Determine the response type
		if ( ! $this->api_service->verify_array_index($request, 'resp'))
		{
			$this->set_response_type('json');
		}
		else
		{
			$this->set_response_type($request['resp']);
		}
	}

	/**
	 * Gets the response type
	 *
	 * @return string
	 */
	public function get_response_type()
	{
		return $this->response_type;
	}

	/**
	 * Sets the response type
	 *
	 * @param $type Type of response for the output data
	 */
	public function set_response_type($type)
	{
		// Set the response type for the API library object
		$this->response_type = $type;

		// Set the response type for the API service
		$this->api_service->set_response_type($type);
	}

	/**
	 * Gets the response data
	 * If the error message has already been set, the error is returned instead
	 *
	 * @return mixed The data fetched by the API request
	 */
	public function get_response_data()
	{
		return (isset($this->error_message))
				? $this->error_message
				: $this->response_data;
	}

	/**
	 * Sets the error message
	 *
	 * @param string $error_message Error message for the Api request
	 */
	public function set_error_message($error_message)
	{
		if (is_array($error_message))
		{
			$this->error_message = $this->array_as_json($error_message);
		}
		else
		{
			$this->error_message = $error_message;
		}
	}

	/**
	 * Abstract method that must be implemented by all subclasses
	 * It is this method that services the API request
	 */
	abstract public function perform_task();

	/**
	 * Response
	 *
	 * @param int ret_value
	 * @param string response_type = XML or JSON
	 * @param string error_message - The error message to display
	 *
	 * @return string
	 */
	protected function response($ret_value, $error_messages='')
	{
		$response = array();

		// Set the record count to zero where the value of @param ret_val <> 0
		$this->record_count = ($ret_value != 0) ? 0 : 1;

		if ($ret_value == 0)
		{
			$response = array(
				"payload" => array(
					"domain" => $this->domain,
					"success" => "true"
				),
				"error" => $this->api_service->get_error_msg(0)
			);
		}
		elseif ($ret_value == 1)
		{
			$response = array(
				"payload" => array(
					"domain" => $this->domain,
					"success" => "false"
				),
				"error" => $this->api_service->get_error_msg(003, '', $error_messages)
			);
		}
		elseif ($ret_value == 2)
		{
			// Authentication Failed. Invalid User or App Key
			$response = array(
				"payload" => array("domain" => $this->domain, "success" =>
					"false"),
				"error" => $this->api_service->get_error_msg(005)
			);
		}
		elseif ($ret_value == 4)
		{
			// No results got from the database query
			$response = array(
				"payload" => array(
					"domain" => $this->domain,
					"success" => "true"
				),
				"error" => $this->api_service->get_error_msg(007)
			);
		}
		else
		{
			$response = array(
				"payload" => array(
					"domain" => $this->domain,
					"success" => "false"
				),
				"error" => $this->api_service->get_error_msg(004)
			);
		}

		return $this->array_as_json($response);
	}

	/**
	 * Creates a JSON response given an array.
	 *
	 * @param array $data Array to be converted to JSON
	 * @return string JSON representation of the data in @param $array
	 */
	protected function array_as_json($data)
	{
		return $this->array2json($data);
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