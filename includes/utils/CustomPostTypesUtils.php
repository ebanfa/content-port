<?php
require('CustomFieldsUtils.php');
require(dirname(dirname(__FILE__)) . '/content-writing/ContentCountryCPT.php');
require(dirname(dirname(__FILE__)) . '/content-writing/ContentTypeOfDocumentCPT.php');
require(dirname(dirname(__FILE__)) . '/content-writing/ContentSubjectAreaCPT.php');
require(dirname(dirname(__FILE__)) . '/content-writing/ContentNoOfPagesCPT.php');
require(dirname(dirname(__FILE__)) . '/content-writing/ContentUrgencyCPT.php');
require(dirname(dirname(__FILE__)) . '/content-writing/ContentAcademicLevelCPT.php');
require(dirname(dirname(__FILE__)) . '/content-writing/ContentCurrencyCPT.php');
require(dirname(dirname(__FILE__)) . '/content-writing/ContentLanguageCPT.php');
require(dirname(dirname(__FILE__)) . '/content-writing/ContentStyleCPT.php');

class CustomPostTypesUtils {

	/*
	This prefix helps ensure unique keys in the $_POST array. It is used only to 
	identify the form elements; this prefix is *not* used as part of the meta_key
	when saving the field names to the database. If you want your fields to be 
	hidden from built-in WordPress functions, you can name them individually 
	using "_" as the first character.
	
	If you omit a prefix entirely, your custom field names must steer clear of
	the built-in post field names (e.g. 'content').
	*/
	public static $prefix = ''; 
	
	// Which types of content do we want to standardize?
	public static $content_types_array = array('content_jobs', 'content_writer', 'content_activity',  'content_countries', 'content_doctypes', 
		'content_subjects', 'content_noofpages', 'content_urgency', 'content_acadlevels', 'content_currencies', 'content_languages', 'content_styles');

	/**
	 * Register the custom post type so it shows up in menus
	 */
	public static function register_custom_post_type()
	{
		ContentJobCPT::register_custom_post_type();
		ContentWriterCPT::register_custom_post_type();
		TimelineActivityCPT::register_custom_post_type();
		ContentCountryCPT::register_custom_post_type();
		ContentTypeOfDocumentCPT::register_custom_post_type();
		ContentSubjectAreaCPT::register_custom_post_type();
		ContentNoOfPagesCPT::register_custom_post_type();
		ContentUrgencyCPT::register_custom_post_type();
		ContentAcademicLevelCPT::register_custom_post_type();
		ContentCurrencyCPT::register_custom_post_type();
		ContentLanguageCPT::register_custom_post_type();
		ContentStyleCPT::register_custom_post_type();
		ContentQuestionCPT::register_custom_post_type();
	}

	/*------------------------------------------------------------------------------
	Save the new Custom Fields values
	INPUT:
		$post_id (int) id of the post these custom fields are associated with
		$post (obj) the post object
	------------------------------------------------------------------------------*/
	public static function save_custom_fields( $post_id, $post ) 
	{
		switch ($post->post_type) 
		{
			case 'content_jobs':
				ContentJobCPT::save_custom_fields($post_id, $post);
				break;
			case 'content_writer':
				ContentWriterCPT::save_custom_fields($post_id, $post);
				break;
			case 'content_activity':
				TimelineActivityCPT::save_custom_fields($post_id, $post);
				break;
			case 'content_countries':
				ContentCountryCPT::save_custom_fields($post_id, $post);
				break;
			case 'content_doctypes':
				ContentTypeOfDocumentCPT::save_custom_fields($post_id, $post);
				break;
			case 'content_subjects':
				ContentSubjectAreaCPT::save_custom_fields($post_id, $post);
				break;
			case 'content_noofpages':
				ContentNoOfPagesCPT::save_custom_fields($post_id, $post);
				break;
			case 'content_urgency':
				ContentUrgencyCPT::save_custom_fields($post_id, $post);
				break;
			case 'content_acadlevels':
				ContentAcademicLevelCPT::save_custom_fields($post_id, $post);
				break;
			case 'content_currencies':
				ContentCurrencyCPT::save_custom_fields($post_id, $post);
				break;
			case 'content_languages':
				ContentLanguageCPT::save_custom_fields($post_id, $post);
				break;
			case 'content_styles':
				ContentStyleCPT::save_custom_fields($post_id, $post);
				break;
			default:
				;
				break;
		}
	}

	/*------------------------------------------------------------------------------
	This plugin is meant to be configured so it acts on a specified list of content
	types, e.g. post, page, or any custom content types that is registered.
	FUTURE: read this from the database.
	------------------------------------------------------------------------------*/
	public static function get_active_content_types()
	{
		return self::$content_types_array;
	}

	//! Public Functions	
	/*------------------------------------------------------------------------------
	* Create the new Custom Fields meta box
	------------------------------------------------------------------------------*/
	public static function create_meta_box() {
		$content_types_array = self::get_active_content_types();
		foreach ($content_types_array as $content_type) {
			add_meta_box( 'my-custom-fields'
				, 'Custom Fields'
				, 'CustomPostTypesUtils::print_custom_fields'
				, $content_type
				, 'normal'
				, 'high'
				, $content_type 
			);
		}
	}

	/**
	 * Display the new Custom Fields meta box
	 * INPUT:
	 * $post (the post object is always passed to this callback function). 
	 * $callback_args will always have a copy of this object passed (I'm not sure why),
	 * but in $callback_args['args'] will be the 7th parameter from the add_meta_box() function.
	 *  We are using this argument to pass the content_type.
	 * @param	post $post - The post.
	 * @param	string	$callback_args 	- The names of call backs
	 */
	public static function print_custom_fields($post, $callback_args='') {
		$content_type = $callback_args['args']; // the 7th arg from add_meta_box()
		$custom_fields = CustomFieldsUtils::get_custom_fields($content_type);
		$output = '';		
		
		foreach ( $custom_fields as $field ) 
		{
			$output_this_field = '';
			$field['name'] = self::$prefix . $field['name']; // this ensures unique keys in $_POST
			
			$field['value'] = htmlspecialchars( get_post_meta( $post->ID, $field['name'], true ) );
			// This will eventually delegate to each CPT to load the correct field value
			$field['value'] = CustomFieldsUtils::get_field_value($content_type, $post->ID, $field);
			switch ( $field['type'] ) 
			{
				case 'checkbox':
					$output_this_field .= CustomFieldsUtils::get_checkbox_element($field);
					break;
				case 'dropdown':
					$output_this_field .= CustomFieldsUtils::get_dropdown_element($field);
					break;
				case 'textarea':
					$output_this_field .= CustomFieldsUtils::get_textarea_element($field);
					break;
				case 'wysiwyg':
					$output_this_field .= CustomFieldsUtils::get_wysiwyg_element($field);
					break;
				case 'text':
				default:
					$output_this_field .= CustomFieldsUtils::get_text_element($field);
					break;
			}
			// optionally add description
			if ( $field['description'] ) 
			{
				$output_this_field .= '<p>'.$field['description'].'</p>';
			}
			$output .= '<div class="form-field form-required">'.$output_this_field.'</div>';
		}
 		// Print the form
 		print '<div class="form-wrap">';
	 	wp_nonce_field('update_custom_content_fields','custom_content_fields_nonce');
	 	print $output;
	 	print '</div>';
	}
	
	/*------------------------------------------------------------------------------
	Remove the default Custom Fields meta box. Only affects the content types that
	have been activated.
	INPUTS: sent from WordPress
	------------------------------------------------------------------------------*/
	public static function remove_default_custom_fields( $type, $context, $post ) {
		$content_types_array = self::get_active_content_types();
		foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
			foreach ( $content_types_array as $content_type )
			{
				remove_meta_box( 'postcustom', $content_type, $context );
			}
		}
	}
}
?>