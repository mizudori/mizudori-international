<?php
/**
 * This class handlies actives submitting data to google docs
 */

class Contact_Forms_Api_Object extends Api_Object {

	public function __construct($api_service)
	{
		parent::__construct($api_service);
	}

	/**
	 * Handler for the API task request
	 */
	public function perform_task()
	{
		if ( ! $this->api_service->verify_array_index($this->request, 'name'))
		{
			$this->set_error_message(array(
				"error" => $this->api_service->get_error_msg(001, 'name')
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

			// Email details
			$this->_email($_POST['email'],'Mizudori Internation - Contact Form', $message, $_POST['name']);
			$this->_reply($_POST['email'],'Thank you for contacting us.', $message, $_POST['name']);
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
		$msg .="We will get back to you shortly. \r\n\r\n";
		$msg .= "Thank you,\r\n";
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