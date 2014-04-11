<?php
/**
 * This class handlies actives submitting data to google docs
 */
class Order_Forms_Api_Object extends Api_Object {

	public function __construct($api_service)
	{
		parent::__construct($api_service);
	}

	/**
	 * Handler for the API task request
	 */
	public function perform_task()
	{
		if ( ! $this->api_service->verify_array_index($this->request, 'task'))
		{
			$this->set_error_message(array(
				"error" => $this->api_service->get_error_msg(001, 'task')
			));
			return;
		}
		else
		{

			// Tag the media and set the response data
			$this->response_data = $this->_post_to_google();
		}
	}



	/**
	 * Post data to google form
	 */
	private function _post_to_google()
	{
		if ($_POST)
		{

			$key_value = array();

			$message = '';
			// For each POST variable as $name_of_input_field => $value_of_input_field
			foreach ($_POST as $key => $value)
			{
				// Set array element for each POST variable
				// $key_value[] = stripslashes($key)."=".stripslashes($value);
				$message .= stripslashes($key).": ".stripslashes($value)."\r\n";
			}

			// Create a query string with join function separted by &
			/*$query_string = join("&", $key_value);

			// Check to see if cURL is installed ...
			if ( ! function_exists('curl_init'))
			{
				die('Sorry cURL is not installed!');
			}

			// The google docs form URL
			$url = $_POST['url'];

			$ch = curl_init();

			// Set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, count($key_value));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);

			// Set some settings that make it all work :)
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);

			// Execute SalesForce web to lead PHP cURL
			$result = curl_exec($ch);

			// close cURL connection
			curl_close($ch);*/

			// Email details
			$this->_email($_POST['email'],'Mizudori International - Order Placed', $message, $_POST['name']);
			$this->_reply($_POST['email'],'Thank you for your order.', $message, $_POST['name']);
			// SUCESS!!!
			$ret = array(
				"payload" => array(
					"domain" => $this->domain,
					"success" => "true"
				),
				"error" => $this->api_service->get_error_msg(0)
			);
			return $this->set_error_message($ret);
		}
		else
		{
			return $this->set_error_message(array("error" =>
				$this->api_service->get_error_msg(004)));
		}
	}

	private function _reply($to, $subject, $message, $full_name)
	{

		$headers = 'From: Mizudori International <do-not-reply@mizudori.jp>'."\r\n".
		'MIME-Version: 1.0'."\r\n".
		'Reply-To: do-not-reply@mizudori.jp'."\r\n".
		'Content-type: text/plain; charset=utf-8'."\r\n".
		'X-Mailer: PHP/'.phpversion();
		$msg = "Dear $full_name,\r\n\r\n";
		$msg .="We acknowledge receipt of your order. \r\n\r\n";
		$msg .="See below for the details of your order.\r\n\r\n";
		$msg .= $message;
		$msg .= "Thank you for your order,\r\n";
		$msg .= "Mizudori International Team";
		mail($to, $subject, $msg, $headers);
	}

	private function _email($to, $subject, $message, $full_name)
	{

		$headers = "From: $full_name <$to>"."\r\n".
		'MIME-Version: 1.0'."\r\n".
		'Reply-To: '.REPLY_EMAIL."\r\n".
		'Content-type: text/plain; charset=utf-8'."\r\n".
		'X-Mailer: PHP/'.phpversion();

		mail(REPLY_EMAIL, $subject, $message, $headers);
	}
}
