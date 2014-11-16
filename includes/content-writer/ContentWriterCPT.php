<?php 

class ContentWriterCPT {

    public static $prefix = ''; 

	public static $custom_fields =	array(
		array('name' => 'cw_writer_code',
            'title' => 'Writer Code',
            'description' => 'The writer code field',
            'type' => 'text',
        ),
        array('name' => 'cw_user_name',
            'title' => 'User name',
            'description' => 'Writer user name field',
            'type' => 'text'
        ),
        array('name' => 'cw_writer_fn',
            'title' => 'First Name',
            'description' => 'First name field',
            'type' => 'text'
        ),
        array('name' => 'cw_writer_ln',
            'title' => 'Last Name',
            'description' => 'Last name field',
            'type' => 'text'
        ),
        array('name' => 'cw_created_date',
            'title' => 'Created Date',
            'description' => 'Created dated field',
            'type' => 'text'
        ),
        array('name' => 'cw_rating',
            'title' => 'Rating',
            'description' => 'Rating field',
            'type' => 'text'
        ),
        array('name' => 'cw_price',
            'title' => 'Writer Price',
            'description' => 'Writer price field',
            'type' => 'text'
        ),
        array('name' => 'cw_email',
            'title' => 'Email',
            'description' => 'Email field',
            'type' => 'text'
        ),
	);

	/**
	 * Register the custom post type so it shows up in menus
	 */
	public static function register_custom_post_type()
	{
	   register_post_type('content_writer', 
			array(
				'label' => 'Content Writer',
				'labels' => array(
				'add_new' 			=> 'Add New',
				'add_new_item'		=> 'Add New Content Writer',
				'edit_item'			=> 'Edit Content Writer',
				'new_item'			=> 'New Content Writer',
				'view_item'			=> 'View Content Writer',
				'search_items'		=> 'Search Content Writers',
				'not_found'			=> 'No content writers Found',
				'not_found_in_trash'=> 'Not Found in Trash',
				),
				'description' => 'Reusable content writer',
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
    public static function content_writer_table_head($defaults) 
    {
        $defaults['content_writer_name'] = 'Name';
        $defaults['content_writer_user'] = 'User Name';
        $defaults['content_writer_email'] = 'Email';
        $defaults['content_writer_price'] = 'Price';
        $defaults['content_writer_rating'] = 'Rating';
        return $defaults;
    }

   /**
    * Customize the Order CPT table content
    */
    public static function content_writer_table_content($column_name, $post_id) 
    {

        if ($column_name == 'content_writer_name') 
        {
          $name = get_post_meta($post_id, 'cw_writer_fn', true ) . ' ' . get_post_meta($post_id, 'cw_writer_ln', true );
          echo $name;
        }
        if ($column_name == 'content_writer_user') 
        {
          $user_name = get_post_meta($post_id, 'cw_user_name', true );
          echo $user_name;
        }
        if ($column_name == 'content_writer_email') 
        {
            $email = get_post_meta($post_id, 'cw_email', true );
            echo $email;
        }
        if ($column_name == 'content_writer_price') 
        {
            $price = get_post_meta($post_id, 'cw_price', true );
            echo $price;
        }
        if ($column_name == 'content_writer_rating') 
        {
            $rating = get_post_meta($post_id, 'cw_rating', true );
            echo $rating;
        }
    }

   /**
    * Customize Orders CPT admin table sortable columns.
    */
    public static function content_writer_table_sorting($columns)
    {
        $columns['content_writer_price'] = 'cw_price';
        return $columns;
    }

   /**
    * Sort the Order CPT table by the order number. Delegates to CPT class.
    */
    public static function content_writer_columns_orderby($vars) 
    {
        if ( isset( $vars['post_type'] ) && 'content_writer' == $vars['post_type'] ) 
        {
            if ( isset( $vars['orderby'] ) && 'content_writer_price' == $vars['orderby'] ) 
            {
                $vars = array_merge( $vars, array('meta_key' => 'cw_price', 'orderby' => 'meta_value_num'));
            }
        }
        return $vars;
    }

    public static function get_field_value($content_type, $post_id, $field) {

        return $field['value'];
    }


    /*------------------------------------------------------------------------------
    Save the new Custom Fields values
    INPUT:
        $post_id (int) id of the post these custom fields are associated with
        $post (obj) the post object
  ------------------------------------------------------------------------------*/
    public static function save_custom_fields( $post_id, $post ) 
    {
        if ( $post->post_type == 'content_writer') {
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