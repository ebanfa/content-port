<?php


class ContentJobOrderCPT {

	public static $prefix = ''; 
	
	public static $custom_fields =	array(
		array('name' => 'jo_order_code',
            'title' => 'Job Order Code',
            'description' => 'Job order code field',
            'type' => 'text',
        ),
        array('name' => 'jo_order_user_name', 
            'title' => 'User Name',
            'description' => 'User name field',
            'type' => 'text'
        ),
        array('name' => 'jo_first_name', 
            'title' => 'First Name',
            'description' => 'First field',
            'type' => 'text'
        ),
        array('name' => 'jo_last_name', 
            'title' => 'Last Name',
            'description' => 'Last field',
            'type' => 'text'
        ),
        array('name' => 'jo_country', 
            'title' => 'Country',
            'description' => 'Country field',
            'type' => 'text'
        ),
        array('name' => 'jo_order_service_code',
            'title' => 'Service Code',
            'description' => 'Service code field',
            'type' => 'text'
        ),
        array('name' => 'jo_order_subtotal',
              'title' => 'Sub Total',
              'description' => 'Sub total field',
              'type' => 'text'
        ), 
        array('name' => 'jo_order_total',
              'title' => 'Total',
              'description' => 'Total field',
              'type' => 'text'
        ),  
        array('name' => 'jo_order_discount',
              'title' => 'Discount Total',
              'description' => 'Discount total field',
              'type' => 'text'
        ),  
        array('name' => 'jo_order_amount_paid',
              'title' => 'Amount Paid',
              'description' => 'Amount paid field',
              'type' => 'text'
        ), 
        array('name' => 'jo_order_curr',
              'title' => 'Currency',
              'description' => 'Currency field',
              'type' => 'text'
        ),
        array('name' => 'jo_order_contact',
              'title' => 'Contact',
              'description' => 'Contact field',
              'type' => 'text'
        ),  
        array('name' => 'jo_order_status',
              'title' => 'Payment Status',
              'description' => 'Payment status field',
              'type' => 'text'
        ),   
        array('name' => 'jo_order_date',
              'title' => 'Order Date',
              'description' => 'Order date field',
              'type' => 'text'
        ),   
        array('name' => 'jo_order_due_date',
              'title' => 'Due Date',
              'description' => 'Due date field',
              'type' => 'text'
        ),
        array('name' => 'jo_agree',
              'title' => 'Agree To Terms',
              'description' => 'Agree to terms field',
              'type' => 'text'
        ),
	);

	/**
	 * Register the custom post type so it shows up in menus
	 */
	public static function register_custom_post_type()
	{
		register_post_type( 'content_joborders', 
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
	public static function content_joborders_table_head($defaults) 
	{
    	$defaults['order_client']  = 'Client Name';
      	$defaults['order_orderno']    = 'Order No';
      	$defaults['order_amount']    = 'Amount';
        $defaults['order_discount']    = 'Discount';
      	$defaults['order_paid']   = 'Paid';
      	$defaults['order_status'] = 'Status';
      	return $defaults;
  	}

	/**
	 * Customize the Order CPT table content
	 */
  	public static function content_joborders_table_content($column_name, $post_id) 
  	{
	    if ($column_name == 'order_client') 
	    {
	        $user_name = get_post_meta($post_id, 'jo_order_user_name', true );
	        echo $user_name;
	    }
	    if ($column_name == 'order_orderno') 
	    {
	        $order_no = get_post_meta($post_id, 'jo_order_code', true );
	        echo $order_no;
	    }
	    if ($column_name == 'order_amount') 
	    {
	        $total = get_post_meta($post_id, 'jo_order_total', true );
	        echo $total;
	    }
	    if ($column_name == 'order_discount') 
	    {
            $pymnt_status = get_post_meta($post_id, 'jo_order_discount', true );
            echo $pymnt_status;
	    }
	    if ($column_name == 'order_paid') 
	    {
            $pymnt_status = get_post_meta($post_id, 'jo_order_amount_paid', true );
            echo $pymnt_status;
	    }
	    if ($column_name == 'order_status') 
	    {
	        $urgency_txt = get_post_meta( $post_id, 'jo_order_status', true );
	        echo $urgency_txt;
	    }
  	}

	/**
	 * Customize Orders CPT admin table sortable columns.
	 */
  	public static function content_joborders_table_sorting($columns){
      	$columns['order_amount']    = 'order_amount';
  		return $columns;
	}

	/**
	 * Sort the Order CPT table by the order number. Delegates to CPT class.
	 */
	public static function content_joborders_columns_orderby($vars) {
		if ( isset( $vars['post_type'] ) && 'content_joborders' == $vars['post_type'] ) 
		{
			if ( isset( $vars['orderby'] ) && 'order_amount' == $vars['orderby'] ) 
			{
	        	$vars = array_merge( $vars, array('meta_key' => 'jo_order_total', 'orderby' => 'meta_value_num'));
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
		if ( $post->post_type == 'content_joborders') {
			// The 2nd arg here is important because there are multiple nonces on the page
			if ( !empty($_POST))// && check_admin_referer('update_custom_content_fields','custom_content_fields_nonce') )
			{			
				$custom_fields = self::$custom_fields;
                CustomFieldsUtils::save_custom_fields($post_id, $post, $custom_fields);
			}
		}
	}

}
?>