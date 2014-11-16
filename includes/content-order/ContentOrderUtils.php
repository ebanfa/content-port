<?php
class ContentOrderUtils {
	
	/*------------------------------------------------------------------------------
	Process IPN messages for web_accept transactions
	------------------------------------------------------------------------------*/
	public static function processPaypalWebAcceptPayment($data) 
	{
		// Get the invoice number of the transaction
		$orderNo = $data['invoice'];
		// Load the order with the specified orderNo
		$orderQuery = self::getOrderQuery($orderNo);
		// Do the loop
		while ($orderQuery->have_posts() ) : $orderQuery->the_post();
			$post = $orderQuery->post;
			// Update payment status
			update_post_meta($post->ID, 'cj_pymnt_status', 'PAID' );
			// Also update the job status
			update_post_meta($post->ID, 'cj_status', 'STARTED' );
		    // Send out payment notifications
			self::sendPaymentReceivedEmail($post);
		endwhile; wp_reset_postdata();
	}

	/*------------------------------------------------------------------------------
	Sends customer and admin payment received notification messages 
	for a specific essay order.
	------------------------------------------------------------------------------*/
	public static function sendPaymentReceivedEmail($order) 
	{
		self::sendOrderEmail($order, 'payment-subject.tpl', 'payment.tpl', 'admin-payment-subject.tpl', 'admin-payment.tpl');
	}

	/*------------------------------------------------------------------------------
	Handler for the 'send_order_created_email' hook. This action is registered
	in index.php. Sends customer and admin order created notification messages 
	for a specific essay order.
	------------------------------------------------------------------------------*/
	static function sendOrderCreatedEmail($orderNo) 
	{
		
		// Load the order with the specified orderNo
		$orderQuery = self::getOrderQuery($orderNo);
		// Do the loop
		while ($orderQuery->have_posts() ) : $orderQuery->the_post();
			$post = $orderQuery->post;
			self::sendOrderEmail($post, 'confirmation-subject.tpl', 
			'confirmation.tpl', 'admin-confirmation-subject.tpl', 'admin-confirmation.tpl');
		endwhile; 
		wp_reset_postdata();
	}

	static function sendOrderEmail($order, $subjectTempl,	$messageTempl, $adminSubjectTempl, $adminMessageTempl) 
	{
		// Get the template context data 
		$dataContext = self::getDataContext($order);
		if($dataContext) {
			// Send an email to the customer
			$customerEmail = get_post_meta($order->ID, 'cj_user_name', true);
			update_post_meta($order->ID, 'orderDataContext', implode("|", $dataContext));
			self::sendEmail($dataContext, $customerEmail, $subjectTempl, $messageTempl, array());
			// Send an email to the site admin
			//$attachment = get_post_meta($order->ID, 'cj_attachment', false);
			//self::sendEmail($dataContext, get_option('admin_email'), $adminSubjectTempl, $adminMessageTempl, $dataContext['attachments']);
			self::sendEmail($dataContext, get_option('cp_notify_orders'), $adminSubjectTempl, $adminMessageTempl, $dataContext['attachments']);
		}
	}

	// Password is needed here cause wordpress does not store clear text password
	public static function sendUserCreatedEmail($userName, $password) 
	{
		// Find the user
		$user = get_user_by('login', $userName );
		if($user) {
			// Get the user data context {username, password etc}
			$dataContext = self::getUserDataContext($user);
			update_user_meta($user->ID, 'userDataContext', implode("|", $dataContext));
			$dataContext['password'] = $password;
			// 2. Send mail to user,
			self::sendEmail($dataContext, $user->user_login, 'account-created-subject.tpl', 'account-created-message.tpl', array());
			// 3 Send mail to the admin
			self::sendEmail($dataContext, get_option('cp_notify_accounts'), 'account-created-subject.tpl', 'account-created-message.tpl', array());
		}
	}


	public static function sendEmail($dataContext, $address, $subjectTempl, $messageTempl, $attachment) 
	{
		// Load the subject and message templates
		$msgTempl = file_get_contents(dirname(dirname(dirname(__FILE__))) .'/templates/' . $messageTempl);
		$msgSubTempl = file_get_contents(dirname(dirname(dirname(__FILE__))) .'/templates/' . $subjectTempl);
		// Fill the templates
		$msgTempl = self::parse($msgTempl, $dataContext);
		$msgSubTempl = self::parse($msgSubTempl, $dataContext);
		// Send the email
		wp_mail($address, $msgSubTempl, $msgTempl, '', $attachment);
	}

	static function getUserDataContext($user)
	{
		$dataContext = array('user_name' => $user->user_login, 'password' => $user->user_pass, 
			'first_name' => $user->first_name , 'last_name' => $user->last_name);
		$dataContext['email'] = $user->user_login;
		return $dataContext;
	}

	static function getDataContext($order) 
	{
		$user = get_user_by( 'email', get_post_meta($order->ID, 'cj_user_name', true ));
		if(is_null($user)) {
			return false;
		}
		$name = $user->first_name.' '.$user->last_name;
		$dataContext = array('first_name' => $user->first_name);
		$dataContext['last_name'] = $user->last_name;
		$dataContext['customer'] = $name;
		$dataContext['order_no'] = get_post_meta($order->ID, 'cj_order_code', true ); 
		$dataContext['order_subtotal'] = get_post_meta($order->ID, 'cj_total', true );
		$dataContext['order_discount'] = 0.00;
		$dataContext['order_total'] = get_post_meta($order->ID, 'cj_total', true );
		$dataContext['date_now'] = (new DateTime())->format(ContentPort::$date_format);
		$dataContext['order_date'] = get_post_meta($order->ID, 'cj_created_date', true );
		$dataContext['order_topic'] = get_post_meta($order->ID, 'cj_topic', true );
		$dataContext['attachments'] = get_post_meta($order->ID, 'cj_attachment', false);
		$dataContext['order_costperpage'] = get_post_meta($order->ID, 'cj_cost_page', true );
		/*
		 * Load the subject area
		 */
        $subjectsQueryArgs = array('numberposts' => -1,	'post_status' => 'any',	'post_type' => 'content_subjects',
	  	'meta_query' => array(array('key' => 'subjectarea_code', 'value' => get_post_meta($order->ID, 'cj_subject', true))));
		$subjectsQuery = new WP_Query($subjectsQueryArgs);
		while ($subjectsQuery->have_posts()) : $subjectsQuery->the_post();
			$subjectarea = $subjectsQuery->post;
    		$dataContext['order_subject'] = get_post_meta($subjectarea->ID, 'subjectarea_name', true );
    	endwhile; wp_reset_postdata(); 
		/*
		 * Load academic Level
		 */
    	$academicLevelQueryArgs = array('numberposts' => -1,	'post_status' => 'any',	'post_type' => 'content_acadlevels',
		  	'meta_query' => array(array('key' => 'academiclevel_code', 'value' => get_post_meta($order->ID, 'cj_academic_level', true))));
		$academiclevelQuery = new WP_Query($academicLevelQueryArgs);
		while ($academiclevelQuery->have_posts()) : $academiclevelQuery->the_post();
			$academiclevel = $academiclevelQuery->post;
        	$dataContext['order_level'] = get_post_meta($academiclevel->ID, 'academiclevel_name', true);
        endwhile; wp_reset_postdata();
		/*
		 * Load No Of Pages
		 */
		$noOfPagesQueryArgs = array('numberposts' => -1,	'post_status' => 'any',	'post_type' => 'content_noofpages',
	  		'meta_query' => array(array('key' => 'noofpages_code', 'value' => get_post_meta($order->ID, 'cj_noofpages', true))));
		$noOfPagesQuery = new WP_Query($noOfPagesQueryArgs);
		while ($noOfPagesQuery->have_posts()) : $noOfPagesQuery->the_post();
			$noOfPages = $noOfPagesQuery->post;
			$dataContext['order_pages'] = get_post_meta($noOfPages->ID, 'noofpages_name', true );
		endwhile; wp_reset_postdata();
		/*
		 * Load Document Type
		 */
		$doctypesQueryArgs = array('numberposts' => -1,	'post_status' => 'any',	'post_type' => 'content_doctypes',
	  		'meta_query' => array(array('key' => 'doctype_code', 'value' => get_post_meta($order->ID, 'cj_document_type', true))));
		$doctypesQuery = new WP_Query($doctypesQueryArgs);
		while ($doctypesQuery->have_posts()) : $doctypesQuery->the_post();
			$doc_type = $doctypesQuery->post;
			$dataContext['order_type'] = get_post_meta($doc_type->ID, 'doctype_name', true );
		endwhile; wp_reset_postdata();
		/*
		 * Load Urgency
		 */
		$urgencyQueryArgs = array('numberposts' => -1,	'post_status' => 'any',	'post_type' => 'content_urgency',
	  		'meta_query' => array(array('key' => 'urgency_code', 'value' => get_post_meta($order->ID, 'cj_urgency', true))));
		$urgencyQuery = new WP_Query($urgencyQueryArgs);
		// Do the loop
		while ($urgencyQuery->have_posts()) : $urgencyQuery->the_post();
			$urgency = $urgencyQuery->post;
	  		$date_val = get_post_meta($urgency->ID, 'urgency_date_value', true );
	  		$date_unit = get_post_meta($urgency->ID, 'urgency_date_unit', true );
			$dataContext['order_term'] = get_post_meta($urgency->ID, 'urgency_name', true );
			$dataContext['due_date'] = ContentPortDateUtils::get_due_date($dataContext['order_date'], $date_val, $date_unit);
		endwhile; wp_reset_postdata();
		/*
		 * Load Currency
		 */
		$currenciesQueryArgs = array('numberposts' => -1,	'post_status' => 'any',	'post_type' => 'content_currencies',
		  	'meta_query' => array(array('key' => 'currency_code', 'value' => get_post_meta($order->ID, 'cj_currency', true))));
		$currenciesQuery = new WP_Query($currenciesQueryArgs);
		while ($currenciesQuery->have_posts()) : $currenciesQuery->the_post();
			$currency = $currenciesQuery->post; 
			$dataContext['order_currency'] = get_post_meta($currency->ID, 'currency_name', true );
		endwhile; wp_reset_postdata();
		return $dataContext;
	}

	/*------------------------------------------------------------------------------
	SYNOPSIS: a simple parsing function for basic templating.
	INPUT:
		$tpl (str): a string containing [+placeholders+]
		$hash (array): an associative array('key' => 'value');
	OUTPUT
		string; placeholders corresponding to the keys of the hash will be replaced
		with the values and the string will be returned.
	------------------------------------------------------------------------------*/
	public static function parse($tpl, $hash) {
	
	    foreach ($hash as $key => $value) {
	        $tpl = str_replace('[+'.$key.'+]', $value, $tpl);
	    }
	    return $tpl;
	}



	static function getOrderQuery($orderNo) 
	{
		$queryArgs = array(
			'post_type' => 'content_jobs',
			'meta_query' => array(array('key' => 'cj_order_code', 'value' => $orderNo)));
		return new WP_Query($queryArgs);
	}
}?>