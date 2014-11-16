<?php 

class ContentCPT {

    public static $prefix = ''; 

	public static $custom_fields =	array(
		array('name' => 'content_name',
            'title' => 'Content Name',
            'description' => 'The name field',
            'type' => 'text',
        ),
        array('name' => 'content_type_code',
            'title' => 'Content Type Code',
            'description' => 'Content type field',
            'type' => 'text'
        ),
        array('name' => 'content_user_name',
            'title' => 'User name',
            'description' => 'Owner user name field',
            'type' => 'text'
        ),
        array('name' => 'content_worker_code',
            'title' => 'Worker Code',
            'description' => 'Job worker code field',
            'type' => 'text'
        ),
        array('name' => 'content_topic',
            'title' => 'Content Topic',
            'description' => 'Content topic field',
            'type' => 'text'
        ),
        array('name' => 'content_size',
            'title' => 'Content size',
            'description' => 'Media type field',
            'type' => 'text'
        ),
        array('name' => 'content_description',
            'title' => 'Job Description',
            'description' => 'Job description field',
            'type' => 'text'
        ),
        array('name' => 'content_created_date',
            'title' => 'Created Date',
            'description' => 'Created dated field',
            'type' => 'text'
        ),
        array('name' => 'content_writing_style',
            'title' => 'Writing Style',
            'description' => 'Writing style field',
            'type' => 'text'
        ),
        array('name' => 'content_subject',
            'title' => 'Subject',
            'description' => 'Subject field',
            'type' => 'text'
        ),
        array('name' => 'content_noofpages',
            'title' => 'No Of Pages',
            'description' => 'Number of pages',
            'type' => 'text'
        ),
        array('name' => 'content_status',
            'title' => 'Job Status',
            'description' => 'Job status field',
            'type' => 'text'
        ),
        array('name' => 'content_lastrev_date',
            'title' => 'Last Revision Date',
            'description' => 'Last revised date field',
            'type' => 'text'
        ),
	);

	/**
	 * Register the custom post type so it shows up in menus
	 */
	public static function register_custom_post_type()
	{
	   register_post_type('content_doc', 
			array(
				'label' => 'Content',
				'labels' => array(
				'add_new' 			=> 'Add New',
				'add_new_item'		=> 'Add New Content',
				'edit_item'			=> 'Edit Content',
				'new_item'			=> 'New Content',
				'view_item'			=> 'View Content',
				'search_items'		=> 'Search Content',
				'not_found'			=> 'No Content Found',
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
    public static function content_doc_table_head($defaults) 
    {
        $defaults['content_user_name'] = 'Customer';
        $defaults['content_worker'] = 'Writer';
        $defaults['content_topic'] = 'Topic';
        $defaults['content_subject'] = 'Subject';
        $defaults['content_noofpages'] = 'Pages';
        $defaults['content_status'] = 'Status';
        return $defaults;
    }

    /**
     * Customize the Order CPT table content
     */
    public static function content_doc_table_content($column_name, $post_id) 
    {
        if ($column_name == 'content_user_name') 
        {
            $user_name = get_post_meta($post_id, 'content_user_name', true );
            echo $user_name;
        }
        if ($column_name == 'content_worker') 
        {
            $content_no = get_post_meta($post_id, 'content_worker_code', true );
            echo $content_no;
        }
        if ($column_name == 'content_topic') 
        {
            $total = get_post_meta($post_id, 'content_topic', true );
            echo $total;
        }
        if ($column_name == 'content_subject') 
        {
            $pymnt_status = get_post_meta($post_id, 'content_subject', true );
            echo $pymnt_status;
        }
        if ($column_name == 'content_noofpages') 
        {
            $pymnt_status = get_post_meta($post_id, 'content_noofpages', true );
            echo $pymnt_status;
        }
        if ($column_name == 'content_status') 
        {
            $urgency_txt = get_post_meta( $post_id, 'content_status', true );
            echo $urgency_txt;
        }
    }

    /**
     * Customize Orders CPT admin table sortable columns.
     */
    public static function content_doc_table_sorting($columns){
        $columns['content_amount']    = 'content_amount';
        return $columns;
    }

    /**
     * Sort the Order CPT table by the order number. Delegates to CPT class.
     */
    public static function content_doc_columns_orderby($vars) {
        if ( isset( $vars['post_type'] ) && 'content_doc' == $vars['post_type'] ) 
        {
            if ( isset( $vars['orderby'] ) && 'content_amount' == $vars['orderby'] ) 
            {
                $vars = array_merge( $vars, array('meta_key' => 'job_content_total', 'orderby' => 'meta_value_num'));
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
        if ( $post->post_type == 'content_doc') 
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