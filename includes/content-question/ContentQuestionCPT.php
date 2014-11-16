<?php

class ContentQuestionCPT {

	public static $prefix = ''; 
	
	public static $custom_fields =	array(
		array('name' => 'paper_type',
              'title' => 'Type of Paper',
              'description' => 'Type of paper field',
              'type' => 'text',
        ),
        array('name' => 'topic',
              'title' => 'Topic',
              'description' => 'Topic field',
              'type' => 'text'
        ),
        array('name' => 'pages',
              'title' => 'Pages',
              'description' => 'Pages field',
              'type' => 'text'
        ),
        array('name' => 'discipline',
              'title' => 'Discipline',
              'description' => 'Discipline field',
              'type' => 'text'
        ), 
        array('name' => 'service_type',
              'title' => 'Type of service',
              'description' => 'Type of service field',
              'type' => 'text',
        ), 
        array('name' => 'format',
              'title' => 'Format',
              'description' => 'Format field',
              'type' => 'text'
        ), 
        array('name' => 'instructions',
              'title' => 'Instructions',
              'description' => 'Instructions field',
              'type' => 'text',
        ),   
        array('name' => 'attachments',
              'title' => 'Attachments Text',
              'description' => 'Attachments field',
              'type' => 'text',
        ), 
	);

	/**
	 * Register the custom post type so it shows up in menus
	 */
	public static function register_custom_post_type()
	{
		register_post_type( 'content_questions', 
			array(
				'label' => 'Questions',
				'labels' => array(
					'add_new' 			=> 'Add New',
					'add_new_item'		=> 'Add New Questions',
					'edit_item'			=> 'Edit Question',
					'new_item'			=> 'New Question',
					'view_item'			=> 'View Question',
					'search_items'		=> 'Search Questions',
					'not_found'			=> 'No ouestions Found',
					'not_found_in_trash'=> 'Not Found in Trash',
				),
				'description' => 'Reusable questions',
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
	 * Customize the Question CPT table header. 
	 */
	public static function content_questions_table_head($defaults) 
	{
    	$defaults['question_topic']  = 'Topic';
      	$defaults['question_paper_type']    = 'Type of Paper';
      	$defaults['question_pages']    = 'Pages';
      	$defaults['question_discipline']   = 'Discipline';
      	$defaults['question_format'] = 'Format';
      	return $defaults;
  	}

	/**
	 * Customize the Question CPT table content
	 */
  	public static function content_questions_table_content($column_name, $post_id) 
  	{
	    if ($column_name == 'question_topic') 
	    {
	        $question_topic = get_post_meta($post_id, 'topic', true );
	        echo $client_name;
	    }
	    if ($column_name == 'question_paper_type') 
	    {
	        $order_no = get_post_meta($post_id, 'paper_type', true );
	        echo $order_no;
	    }
	    if ($column_name == 'question_pages') 
	    {
	        $total = get_post_meta($post_id, 'pages', true );
	        echo $total;
	    }
	    if ($column_name == 'question_discipline') 
	    {
          $pymnt_status = get_post_meta($post_id, 'discipline', true );
          echo $pymnt_status;
	    }
	    if ($column_name == 'question_format') 
	    {
            $order_date = get_post_meta($post_id, 'format', true );
            echo $order_date;
            
	    }
  	}

	/**
	 * Customize Question CPT admin table sortable columns.
	 */
  	public static function content_questions_table_sorting($columns){
      	$columns['question_pages']    = 'question_pages';
  		return $columns;
	}

	/**
	 * Sort the Question CPT table by the order number. Delegates to CPT class.
	 */
	public static function content_questions_columns_orderby($vars) {
		if ( isset( $vars['post_type'] ) && 'content_questions' == $vars['post_type'] ) 
		{
			if ( isset( $vars['orderby'] ) && 'question_pages' == $vars['orderby'] ) 
			{
	        	$vars = array_merge( $vars, array('meta_key' => 'pages', 'orderby' => 'meta_value_num'));
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
		if ( $post->post_type == 'content_questions') {
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