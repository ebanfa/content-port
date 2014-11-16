<?php 

class TimelineActivityCPT {

    public static $prefix = ''; 

	public static $custom_fields =	array(
		array('name' => 'activity_code',
            'title' => 'Activity Code',
            'description' => 'The activity code field',
            'type' => 'text',
        ),
        array('name' => 'activity_type_code',
            'title' => 'Activity Type Code',
            'description' => 'Activity type field',
            'type' => 'text'
        ),
        array('name' => 'activity_item_code',
            'title' => 'Activity Item Code',
            'description' => 'Activity Item field',
            'type' => 'text'
        ),
        array('name' => 'activity_user_name',
            'title' => 'User name',
            'description' => 'Owner user name field',
            'type' => 'text'
        ),
        array('name' => 'activity_document_code',
            'title' => 'Document Code',
            'description' => 'Document code field',
            'type' => 'text'
        ),
        array('name' => 'activity_heading',
            'title' => 'Heading',
            'description' => 'Heading field',
            'type' => 'text'
        ),
        array('name' => 'activity_description',
            'title' => 'Description',
            'description' => 'Description field',
            'type' => 'text'
        ),
        array('name' => 'activity_created_date',
            'title' => 'Created Date',
            'description' => 'Created dated field',
            'type' => 'text'
        ),
	);

	/**
	 * Register the custom post type so it shows up in menus
	 */
	public static function register_custom_post_type()
	{
	   register_post_type('content_activity', 
			array(
				'label' => 'Time Line Items',
				'labels' => array(
				'add_new' 			=> 'Add New',
				'add_new_item'		=> 'Add New Item',
				'edit_item'			=> 'Edit Item',
				'new_item'			=> 'New Item',
				'view_item'			=> 'View Item',
				'search_items'		=> 'Search Item',
				'not_found'			=> 'No Item Found',
				'not_found_in_trash'=> 'Not Found in Trash',
				),
				'description' => 'Reusable content',
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
    public static function content_activity_table_head($defaults) 
    {
        $defaults['activity_code'] = 'Code';
        $defaults['activity_type_code'] = 'Type';
        $defaults['activity_item_code'] = 'Item Code';
        $defaults['activity_user_name'] = 'User Name';
        $defaults['activity_document_code'] = 'Document';
        return $defaults;
    }

   /**
    * Customize the Order CPT table content
    */
    public static function content_activity_table_content($column_name, $post_id) 
    {
        if ($column_name == 'activity_code') 
        {
          $user_name = get_post_meta($post_id, 'activity_code', true );
          echo $user_name;
        }
        if ($column_name == 'activity_type_code') 
        {
          $order_no = get_post_meta($post_id, 'activity_type_code', true );
          echo $order_no;
        }
        if ($column_name == 'activity_item_code') 
        {
          $total = get_post_meta($post_id, 'activity_item_code', true );
          echo $total;
        }
        if ($column_name == 'activity_user_name') 
        {
            $pymnt_status = get_post_meta($post_id, 'activity_user_name', true );
            echo $pymnt_status;
        }
        if ($column_name == 'activity_document_code') 
        {
            $pymnt_status = get_post_meta($post_id, 'activity_document_code', true );
            echo $pymnt_status;
        }
    }

   /**
    * Customize Orders CPT admin table sortable columns.
    */
    public static function content_activity_table_sorting($columns)
    {
        $columns['activity_amount'] = 'joitem_total';
        return $columns;
    }

   /**
    * Sort the Order CPT table by the order number. Delegates to CPT class.
    */
    public static function content_activity_columns_orderby($vars) 
    {
        if ( isset( $vars['post_type'] ) && 'content_activity' == $vars['post_type'] ) 
        {
            if ( isset( $vars['orderby'] ) && 'activity_amount' == $vars['orderby'] ) 
            {
                $vars = array_merge( $vars, array('meta_key' => 'activity_code', 'orderby' => 'meta_value_num'));
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
    public static function save_custom_fields( $post_id, $post) 
    {
        if ( $post->post_type == 'content_activity') 
        {
            // The 2nd arg here is important because there are multiple nonces on the page
            if ( !empty($_POST))// && check_admin_referer('update_custom_content_fields','custom_content_fields_nonce') )
            {     
                CustomFieldsUtils::save_custom_fields($post_id, $post, self::$custom_fields);
            }
        }
    }

}

?>