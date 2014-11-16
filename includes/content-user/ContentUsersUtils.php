<?php

/*------------------------------------------------------------------------------
Handles user related functionality
------------------------------------------------------------------------------*/
class  ContentUsersUtils{
	/*
	 * Add a new user account if it does not exist.
	 * Returns the newly created or pre existing account.
	 */
	public static function add_user($userdata)
	{
		if(null != $userdata && isset($userdata['user_login']))
		{
			if(null == username_exists($userdata['user_login'])) 
			{
				$password = $userdata['user_pass'];
				// Generate the password and create the user
				if (!isset($password) || trim($password)==='') { 
				    $userdata['user_pass'] = wp_generate_password(12, false);
				}  
				// Insert the user
				$user_id = wp_insert_user($userdata);
				if(!is_wp_error($user_id))
				{
					ContentOrderUtils::sendUserCreatedEmail($userdata['user_login'], $userdata['user_pass']);
				}
			} // end if
		}
	}

	static function sendUserEmail($userdata, $subjectTempl, $messageTempl) 
	{
		// Get the template context data 
		$dataContext = self::getDataContext($userdata);
		if($dataContext) {
			// Send an email to the customer
			self::sendEmail($dataContext, $subjectTempl, $messageTempl, array());
		}
	}

	public static function sendEmail($dataContext, $subjectTempl, $messageTempl, $attachment) 
	{
		// Load the subject and message templates
		$msgTempl = file_get_contents(dirname(dirname(dirname(__FILE__))) .'/templates/' . $messageTempl);
		$msgSubTempl = file_get_contents(dirname(dirname(dirname(__FILE__))) .'/templates/' . $subjectTempl);
		// Fill the templates
		$msgTempl = CustomFieldsUtils::parse($msgTempl, $dataContext);
		$msgSubTempl = CustomFieldsUtils::parse($msgSubTempl, $dataContext);
		// Send the email
		wp_mail($dataContext['user_login'], $msgSubTempl, $msgTempl, '', $attachment);
	}

	static function getDataContext($userdata) 
	{
		return $userdata;
	}
}
?>