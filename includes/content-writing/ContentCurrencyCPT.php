<?php 

class ContentCurrencyCPT {

    public static $prefix = ''; 

	public static $custom_fields =	array(
		array('name' => 'currency_name',
              'title' => 'Name',
              'description' => 'Name field',
              'type' => 'text',
        ),
        array('name' => 'currency_code',
              'title' => 'Code',
              'description' => 'Code field',
              'type' => 'text'
        ),
	);

	/**
	 * Register the custom post type so it shows up in menus
	 */
	public static function register_custom_post_type()
	{
	 register_post_type('content_currencies', 
			array(
				'label' => 'Currencies',
				'labels' => array(
					'add_new' 			=> 'Add New',
					'add_new_item'		=> 'Add New Currency',
					'edit_item'			=> 'Edit Currency',
					'new_item'			=> 'New Currency',
					'view_item'			=> 'View Currency',
					'search_items'		=> 'Search Countries',
					'not_found'			=> 'No Currency Found',
					'not_found_in_trash'=> 'Not Found in Trash',
				),
				'description' => 'Reusable currency of content',
				'public' => true,
				'show_ui' => true,
				'menu_position' => 5,
				'supports' => array('title', 'custom-fields'),
				'has_archive'   => true,
				'rewrite'   => true,
			)
		);		
	}

  /*------------------------------------------------------------------------------
  Save the new Custom Fields values
  INPUT:
    $post_id (int) id of the post these custom fields are associated with
    $post (obj) the post object
  ------------------------------------------------------------------------------*/
    public static function save_custom_fields( $post_id, $post ) 
    {
        if ( $post->post_type == 'content_currencies') 
        {
            // The 2nd arg here is important because there are multiple nonces on the page
            if ( !empty($_POST))// && check_admin_referer('update_custom_content_fields','custom_content_fields_nonce') )
            {     
                $custom_fields = CustomFieldsUtils::get_custom_fields($post->post_type);
                // Loop through all the fields
                foreach ( $custom_fields as $field ) 
                {
                    // Processing all fields apart except attachment field here
                    if (isset( $_POST[ self::$prefix . $field['name'] ] ) ) 
                    {
                        $value = trim($_POST[ self::$prefix . $field['name'] ]);
                        // Auto-paragraphs for any WYSIWYG
                        if ( $field['type'] == 'wysiwyg' ) 
                        {
                            $value = wpautop( $value );
                        }
                        update_post_meta( $post_id, $field[ 'name' ], $value );
                    }
                    // if not set, then it's an unchecked checkbox, so blank out the value.
                    else {
                        //update_post_meta( $post_id, $field[ 'name' ], '' );
                    }
                }
            }
        }
    }

}

?>