<?php

require(dirname(dirname(__FILE__)) . '/utils/ContentPortDateUtils.php');

class ContentOrderCPT {

	public static $prefix = ''; 
	
	public static $custom_fields =	array(
        array('name' => 'email',
              'title' => 'Email',
              'description' => 'Email field',
              'type' => 'text'
        ),
        array('name' => 'topic',
              'title' => 'Topic',
              'description' => 'Topic field',
              'type' => 'text'
        ), 
        array('name' => 'document_type',
              'title' => 'Type Of Document',
              'description' => 'Type of document field',
              'type' => 'dropdown',
              'options' => array('Essay','Term Paper','Research Paper'),
        ),   
        array('name' => 'document_type_txt',
              'title' => 'Type Of Document Text',
              'description' => 'Type of document text field',
              'type' => 'text',
        ),  
        array('name' => 'subject_area',
              'title' => 'Subject Area',
              'description' => 'Subject area field',
              'type' => 'dropdown',
              'options' => array('Art','Biology','Business'),
        ), 
        array('name' => 'subject_area_txt',
              'title' => 'Subject Area Text',
              'description' => 'Subject area text field',
              'type' => 'text',
        ), 
        array('name' => 'numpages',
              'title' => 'Number Of Pages',
              'description' => 'Number of pages field',
              'type' => 'dropdown',
              'options' => array('1 page/approx 550 words','1 page/approx 550 words','1 page/approx 550 words'),
        ), 
        array('name' => 'o_interval',
              'title' => 'Line Spacing',
              'description' => 'Line Spacing',
              'type' => 'text',
        ),  
        array('name' => 'urgency',
              'title' => 'Urgency',
              'description' => 'Urgency field',
              'type' => 'text',
        ),   
        array('name' => 'urgency_txt',
              'title' => 'Urgency Text',
              'description' => 'Urgency text field',
              'type' => 'text',
        ), 
        array('name' => 'academic_level',
              'title' => 'Academic Level',
              'description' => 'Academic level field',
              'type' => 'dropdown',
              'options' => array('High School','Undergraduate','Master', 'Ph. D.'),
        ),  
        array('name' => 'academic_level_txt',
              'title' => 'Academic Level Text',
              'description' => 'Academic level text field',
              'type' => 'text',
        ),  
        array('name' => 'language',
              'title' => 'Language',
              'description' => 'Language field',
              'type' => 'dropdown',
              'options' => array('ENGLISH(US)','ENGLISH(UK)'),
        ),   
        array('name' => 'style',
              'title' => 'Style',
              'description' => 'Style field',
              'type' => 'dropdown',
              'options' => array('ENGLISH(US)','ENGLISH(UK)'),
        ),  
        array('name' => 'costperpage',
              'title' => 'Cost Per Page',
              'description' => 'Cost per page field',
              'type' => 'text'
        ),  
        array('name' => 'total',
              'title' => 'Total',
              'description' => 'Total field',
              'type' => 'text'
        ), 
        array('name' => 'curr',
              'title' => 'Currency',
              'description' => 'Currency field',
              'type' => 'text'
        ),   
        array('name' => 'curr_txt',
              'title' => 'Currency Text',
              'description' => 'Currency text field',
              'type' => 'text',
        ),    
        array('name' => 'order_no',
              'title' => 'Order No',
              'description' => 'Order No field',
              'type' => 'text'
        ),     
        array('name' => 'order_usr',
              'title' => 'Order User',
              'description' => 'Order user field',
              'type' => 'text'
        ),   
        array('name' => 'attachment',
              'title' => 'Attachment',
              'description' => 'Attachment field',
              'type' => 'text'
        ),   
        array('name' => 'order_instructions',
              'title' => 'Order Instructions',
              'description' => 'Order instructions field',
              'type' => 'textarea'
        ),    
        array('name' => 'allow_calls',
              'title' => 'Receive phone calls',
              'description' => 'Receive phone calls field',
              'type' => 'checkbox'
        ), 
        array('name' => 'agree',
              'title' => 'I agree to terms and conditions',
              'description' => 'I agree to terms and conditions field',
              'type' => 'checkbox'
        ),   
        array('name' => 'pymnt_status',
              'title' => 'Payment Status',
              'description' => 'Payment status field',
              'type' => 'text'
        ),   
        array('name' => 'order_date',
              'title' => 'Order Date',
              'description' => 'Order date field',
              'type' => 'text'
        ),   
        array('name' => 'due_date',
              'title' => 'Due Date',
              'description' => 'Due date field',
              'type' => 'text'
        ),
	);

	/**
	 * Register the custom post type so it shows up in menus
	 */
	public static function register_custom_post_type()
	{
		register_post_type( 'content_orders', 
			array(
				'label' => 'Orders',
				'labels' => array(
					'add_new' 			=> 'Add New',
					'add_new_item'		=> 'Add New Order',
					'edit_item'			=> 'Edit Order',
					'new_item'			=> 'New Order',
					'view_item'			=> 'View Order',
					'search_items'		=> 'Search Orders',
					'not_found'			=> 'No orders Found',
					'not_found_in_trash'=> 'Not Found in Trash',
				),
				'description' => 'Reusable orders of content',
				'public' => true,
				'show_ui' => true,
				'menu_position' => 5,
				'supports' => array('title', 'custom-fields'),
				'has_archive'   => true,
				'rewrite'   => true,
			)
		);		
	}

	/**
	 * Customize the Order CPT table header. 
	 */
	public static function content_orders_table_head($defaults) 
	{
    	$defaults['order_client']  = 'Client Name';
      	$defaults['order_orderno']    = 'Order No';
      	$defaults['order_cost']    = 'Cost';
      	$defaults['order_urgency']   = 'Urgency';
      	$defaults['order_payment'] = 'Payment';
      	$defaults['order_time']   = 'Time Remaining';
      	return $defaults;
  	}

	/**
	 * Customize the Order CPT table content
	 */
  	public static function content_orders_table_content($column_name, $post_id) 
  	{
	    if ($column_name == 'order_client') 
	    {
	        $first_name = get_post_meta($post_id, 'firstname', true );
	        $last_name = get_post_meta($post_id, 'lastname', true );
	        $client_name = $first_name . ' ' . $last_name;
	        echo $client_name;
	    }
	    if ($column_name == 'order_orderno') 
	    {
	        $order_no = get_post_meta($post_id, 'order_no', true );
	        echo $order_no;
	    }
	    if ($column_name == 'order_cost') 
	    {
	        $total = get_post_meta($post_id, 'total', true );
	        echo $total;
	    }
	    if ($column_name == 'order_payment') 
	    {
          $pymnt_status = get_post_meta($post_id, 'pymnt_status', true );
          echo $pymnt_status;
	    }
	    if ($column_name == 'order_time') 
	    {
            $order_date = get_post_meta($post_id, 'order_date', true );
            echo $order_date;
            // Only process when we have a valid
            if($order_date != '' || $order_date != ' ')
            {
                $date_val = '';
                $date_unit = '';
                $urgency_code = get_post_meta($post_id, 'urgency', true );
                // Load the Urgency CPT
                $queryArgs = array('post_type' => 'content_urgency',
                  'meta_query' => array(array('key' => 'urgency_code', 'value' => $urgency_code)));
                $urgencyQuery = new WP_Query($queryArgs);
                // Do the loop
                while ($urgencyQuery->have_posts()) : $urgencyQuery->the_post();
                    $urgency = $urgencyQuery->post;
                    $date_val = get_post_meta($urgency->ID, 'urgency_date_value', true );
                    $date_unit = get_post_meta($urgency->ID, 'urgency_date_unit', true );
                endwhile; 
                wp_reset_postdata();
                // Only process if we have valid values
                if($date_val != '' && $date_unit != '') 
                {
                    // The date interval
                    $date_interval = 'P';
                    if($date_unit == 'H'){
                        $date_interval = 'PT';
                    }
                    $date_interval = $date_interval . $date_val . $date_unit;
                    // Order date
                    $order_date_time = DateTime::createFromFormat(ContentPort::$date_format, $order_date);
                    // Process the order date
                    if($order_date_time) {
                        // Due date
                        $order_date_time->add(new DateInterval($date_interval));
                        // The difference between the dates
                        $time_now = new DateTime();
                        $date_difference = $time_now->diff($order_date_time);
                        // Print out the interval
                        $time_remaining = $date_difference->format('%d days, %h hours, %i minutes');
                        echo $time_remaining;
                    }
                }
            }
	    }
	    if ($column_name == 'order_urgency') 
	    {
	        $urgency_txt = get_post_meta( $post_id, 'urgency_txt', true );
	        echo $urgency_txt;
	    }
  	}

	/**
	 * Customize Orders CPT admin table sortable columns.
	 */
  	public static function content_orders_table_sorting($columns){
      	$columns['order_cost']    = 'order_cost';
  		return $columns;
	}

	/**
	 * Sort the Order CPT table by the order number. Delegates to CPT class.
	 */
	public static function content_orders_columns_orderby($vars) {
		if ( isset( $vars['post_type'] ) && 'content_orders' == $vars['post_type'] ) 
		{
			if ( isset( $vars['orderby'] ) && 'order_cost' == $vars['orderby'] ) 
			{
	        	$vars = array_merge( $vars, array('meta_key' => 'total', 'orderby' => 'meta_value_num'));
		    }
		}
    	return $vars;
	}


	/*------------------------------------------------------------------------------
	Save the new Custom Fields values
	INPUT:
		$post_id (int) id of the post these custom fields are associated with
		$post (obj) the post object
	------------------------------------------------------------------------------*/
	public static function save_custom_fields( $post_id, $post ) 
	{
		if ( $post->post_type == 'content_orders') {
			// The 2nd arg here is important because there are multiple nonces on the page
			if ( !empty($_POST))// && check_admin_referer('update_custom_content_fields','custom_content_fields_nonce') )
			{			
				$custom_fields = CustomFieldsUtils::get_custom_fields($post->post_type);
				// Loop through all the fields
				foreach ( $custom_fields as $field ) {
					// Processing all fields apart except attachment field here
					if($field['name'] != 'attachment') 
					{
						if (isset( $_POST[ self::$prefix . $field['name'] ] ) ) 
						{
							$value = trim($_POST[ self::$prefix . $field['name'] ]);
							// Auto-paragraphs for any WYSIWYG
							if ( $field['type'] == 'wysiwyg' ) 
							{
								$value = wpautop( $value );
							}
							update_post_meta( $post_id, $field[ 'name' ], $value );
                // Save the due date
                if($field[ 'name' ] == 'order_date') {
                    // Set the due date
                    $due_date = ContentPortDateUtils::get_due_date($post_id);
                    update_post_meta( $post_id, 'due_date', $due_date);
                }
						}
						// if not set, then it's an unchecked checkbox, so blank out the value.
						else {
							//update_post_meta( $post_id, $field[ 'name' ], '' );
						}
					} 
					// Process attachments by reading $_FILES
					// Am assuming that all validations has taken place at the template level
					// So since execution has reached here we will just go ahead an save the files
					else {
						$count = 0;
			            foreach ($_FILES['order_attachment']['name'] as $filename) 
			            {
			            	if ($_FILES['order_attachment']['tmp_name'][$count] != '') 
        					{
				            	// Use the WordPress API to upload the file
		                        $upload = wp_upload_bits($_FILES['order_attachment']['name'][$count], 
		                            null, file_get_contents($_FILES['order_attachment']['tmp_name'][$count]));
		                        if(isset($upload['error']) && $upload['error'] != 0) {
		                            wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
		                        } else {
		                            add_post_meta($post_id, 'attachment', $upload['file']);    
		                        } // end if/else
        					}
                			$count++;
			            }
					}
				}// End for loop
			}
		}
	}

}
?>