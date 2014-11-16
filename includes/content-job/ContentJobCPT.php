<?php 

class ContentJobCPT {

    public static $prefix = ''; 

	public static $custom_fields =	array(
		array('name' => 'cj_order_code',
            'title' => 'Order Code',
            'description' => 'The order code field',
            'type' => 'text',
        ),
        array('name' => 'cj_user_name',
            'title' => 'User name',
            'description' => 'Owner user name field',
            'type' => 'text'
        ),
        array('name' => 'cj_topic',
            'title' => 'Topic',
            'description' => 'Job topic field',
            'type' => 'text'
        ),
        array('name' => 'cj_subject',
            'title' => 'Subject',
            'description' => 'Subject field',
            'type' => 'text'
        ),
        array('name' => 'cj_term',
            'title' => 'Job Term',
            'description' => 'Job term field',
            'type' => 'text'
        ),
        array('name' => 'cj_noofpages',
            'title' => 'No Of Pages',
            'description' => 'Number of pages',
            'type' => 'text'
        ),
        array('name' => 'cj_line_spacing',
            'title' => 'Line Spacing',
            'description' => 'Line Spacing',
            'type' => 'text'
        ),
        array('name' => 'cj_urgency',
            'title' => 'Urgency',
            'description' => 'Job urgency field',
            'type' => 'text'
        ),
        array('name' => 'cj_language',
            'title' => 'Language',
            'description' => 'Languages',
            'type' => 'text'
        ),
        array('name' => 'cj_academic_level',
            'title' => 'Academic Level',
            'description' => 'Academic Levels',
            'type' => 'text'
        ),
        array('name' => 'cj_document_type',
            'title' => 'Document Type',
            'description' => 'Document Types',
            'type' => 'text'
        ),
        array('name' => 'cj_created_date',
            'title' => 'Created Date',
            'description' => 'Created dated field',
            'type' => 'text'
        ),
        array('name' => 'cj_started_date',
            'title' => 'Started Date',
            'description' => 'Started dated field',
            'type' => 'text'
        ),
        array('name' => 'cj_writing_style',
            'title' => 'Writing Style',
            'description' => 'Writing style field',
            'type' => 'text'
        ),
        array('name' => 'cj_instructions',
            'title' => 'Job Instruction',
            'description' => 'Job instruction field',
            'type' => 'text'
        ),
        array('name' => 'cj_attachment',
            'title' => 'Job Attachment',
            'description' => 'Job attachment field',
            'type' => 'text'
        ),
        array('name' => 'cj_status',
            'title' => 'Job Status',
            'description' => 'Job status field',
            'type' => 'text'
        ),
        array('name' => 'cj_cost_page',
              'title' => 'Cost Per Page',
              'description' => 'Cost per page field',
              'type' => 'text'
        ),  
        array('name' => 'cj_total',
              'title' => 'Total',
              'description' => 'Total field',
              'type' => 'text'
        ), 
        array('name' => 'cj_currency',
              'title' => 'Currency',
              'description' => 'Currency field',
              'type' => 'text'
        ),  
        array('name' => 'cj_pymnt_status',
              'title' => 'Payment Status',
              'description' => 'Payment status field',
              'type' => 'text'
        ),   
	);

	/**
	 * Register the custom post type so it shows up in menus
	 */
	public static function register_custom_post_type()
	{
	   register_post_type('content_jobs', 
			array(
				'label' => 'Content Job',
				'labels' => array(
				'add_new' 			=> 'Add New',
				'add_new_item'		=> 'Add New Content Job',
				'edit_item'			=> 'Edit Content Job',
				'new_item'			=> 'New Content Job',
				'view_item'			=> 'View Content Job',
				'search_items'		=> 'Search Content Jobs',
				'not_found'			=> 'No content jobs Found',
				'not_found_in_trash'=> 'Not Found in Trash',
				),
				'description' => 'Reusable content job',
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
    public static function content_jobs_table_head($defaults) 
    {
        $defaults['content_job_user_name'] = 'Customer';
        $defaults['content_job_topic'] = 'Topic';
        $defaults['content_job_noofpages'] = 'Pages';
        $defaults['content_job_unit_cost'] = 'Cost Per Page';
        $defaults['content_job_amount'] = 'Amount';
        $defaults['content_job_term'] = 'Term';
        $defaults['content_job_expires'] = 'Expires';
        $defaults['content_job_status'] = 'Status';
        return $defaults;
    }

   /**
    * Customize the Order CPT table content
    */
    public static function content_jobs_table_content($column_name, $post_id) 
    {
        /*
         * Load Urgency
         */
        $urgencyQueryArgs = array('numberposts' => -1,  'post_status' => 'any', 'post_type' => 'content_urgency',
            'meta_query' => array(array('key' => 'urgency_code', 'value' => get_post_meta($post_id, 'cj_urgency', true))));
        $urgencyQuery = new WP_Query($urgencyQueryArgs);
        // Do the loop
        while ($urgencyQuery->have_posts()) : $urgencyQuery->the_post();
            $urgency = $urgencyQuery->post;
            $job_term = get_post_meta($urgency->ID, 'urgency_name', true);
            $date_val = get_post_meta($urgency->ID, 'urgency_date_value', true );
            $date_unit = get_post_meta($urgency->ID, 'urgency_date_unit', true );
            $date_created = get_post_meta($post_id, 'cj_created_date', true );
            $job_expires = ContentPortDateUtils::get_due_date($date_created, $date_val, $date_unit);
        endwhile; wp_reset_postdata();

        if ($column_name == 'content_job_user_name') 
        {
          $user_name = get_post_meta($post_id, 'cj_user_name', true );
          echo $user_name;
        }
        if ($column_name == 'content_job_topic') 
        {
          $topic = get_post_meta($post_id, 'cj_topic', true );
          echo $topic;
        }
        if ($column_name == 'content_job_noofpages') 
        {
            $job_pages = get_post_meta($post_id, 'cj_noofpages', true );
            echo $job_pages;
        }
        if ($column_name == 'content_job_term') 
        {
            echo $job_term;
        }
        if ($column_name == 'content_job_amount') 
        {
            $job_total = get_post_meta($post_id, 'cj_total', true );
            echo $job_total;
        }
        if ($column_name == 'content_job_unit_cost') 
        {
            $job_cost_page = get_post_meta($post_id, 'cj_cost_page', true );
            echo $job_cost_page;
        }
        if ($column_name == 'content_job_expires') 
        {
            echo $job_expires;
        }
        if ($column_name == 'content_job_status') 
        {
            $status = get_post_meta($post_id, 'cj_status', true );
            if($status == 'NOT_STARTED')
                echo 'Not Started';
            if($status == 'STARTED')
                echo 'Started';
            if($status == 'COMPLETED')
                echo 'Completed';
        }
    }

   /**
    * Customize Orders CPT admin table sortable columns.
    */
    public static function content_jobs_table_sorting($columns)
    {
        $columns['content_job_amount'] = 'cj_noofpages';
        return $columns;
    }

   /**
    * Sort the Order CPT table by the order number. Delegates to CPT class.
    */
    public static function content_jobs_columns_orderby($vars) 
    {
        if ( isset( $vars['post_type'] ) && 'content_jobs' == $vars['post_type'] ) 
        {
            if ( isset( $vars['orderby'] ) && 'content_job_noofpages' == $vars['orderby'] ) 
            {
                $vars = array_merge( $vars, array('meta_key' => 'cj_noofpages', 'orderby' => 'meta_value_num'));
            }
        }
        return $vars;
    }

    public static function get_field_value($content_type, $post_id, $field) {

        if($field['name'] == 'cj_subject') {
            $subject = '';
            /*
             * Load the subject area
             */
            $subjectsQueryArgs = array('numberposts' => -1, 'post_status' => 'any', 'post_type' => 'content_subjects',
            'meta_query' => array(array('key' => 'subjectarea_code', 'value' => $field['value'])));
            $subjectsQuery = new WP_Query($subjectsQueryArgs);
            while ($subjectsQuery->have_posts()) : $subjectsQuery->the_post();
                $subjectarea = $subjectsQuery->post;
                $subject = get_post_meta($subjectarea->ID, 'subjectarea_name', true );
            endwhile; wp_reset_postdata(); 
            return $subject;
        }
        elseif($field['name'] == 'cj_academic_level') {
            $academic_level = '';
            /*
             * Load academic Level
             */
            $academicLevelQueryArgs = array('numberposts' => -1,    'post_status' => 'any', 'post_type' => 'content_acadlevels',
                'meta_query' => array(array('key' => 'academiclevel_code', 'value' => $field['value'])));
            $academiclevelQuery = new WP_Query($academicLevelQueryArgs);
            while ($academiclevelQuery->have_posts()) : $academiclevelQuery->the_post();
                $academiclevel = $academiclevelQuery->post;
                $academic_level = get_post_meta($academiclevel->ID, 'academiclevel_name', true);
            endwhile; wp_reset_postdata();
            return $academic_level;
        }
        elseif($field['name'] == 'cj_noofpages') {
            $noofpages = '';
            /*
             * Load No Of Pages
             */
            $noOfPagesQueryArgs = array('numberposts' => -1,    'post_status' => 'any', 'post_type' => 'content_noofpages',
                'meta_query' => array(array('key' => 'noofpages_code', 'value' => $field['value'])));
            $noOfPagesQuery = new WP_Query($noOfPagesQueryArgs);
            while ($noOfPagesQuery->have_posts()) : $noOfPagesQuery->the_post();
                $noOfPages = $noOfPagesQuery->post;
                $noofpages = get_post_meta($noOfPages->ID, 'noofpages_name', true );
            endwhile; wp_reset_postdata();
            return $noofpages;
        }
        elseif($field['name'] == 'cj_document_type') {
            $document_type = '';
            /*
             * Load Document Type
             */
            $doctypesQueryArgs = array('numberposts' => -1, 'post_status' => 'any', 'post_type' => 'content_doctypes',
                'meta_query' => array(array('key' => 'doctype_code', 'value' => $field['value'])));
            $doctypesQuery = new WP_Query($doctypesQueryArgs);
            while ($doctypesQuery->have_posts()) : $doctypesQuery->the_post();
                $doc_type = $doctypesQuery->post;
                $document_type = get_post_meta($doc_type->ID, 'doctype_name', true );
            endwhile; wp_reset_postdata();
            return $document_type;
        }
        elseif($field['name'] == 'cj_urgency') {
            $doc_urgency = '';
            /*
             * Load Urgency
             */
            $urgencyQueryArgs = array('numberposts' => -1,  'post_status' => 'any', 'post_type' => 'content_urgency',
                'meta_query' => array(array('key' => 'urgency_code', 'value' => $field['value'])));
            $urgencyQuery = new WP_Query($urgencyQueryArgs);
            // Do the loop
            while ($urgencyQuery->have_posts()) : $urgencyQuery->the_post();
                $urgency = $urgencyQuery->post;
                $doc_urgency = get_post_meta($urgency->ID, 'urgency_name', true );
            endwhile; wp_reset_postdata();
            return $doc_urgency;
        }
        elseif($field['name'] == 'cj_language') {
            $doc_language = '';
            /*
             * Load Urgency
             */
            $languagesQueryArgs = array('numberposts' => -1,  'post_status' => 'any', 'post_type' => 'content_languages',
                'meta_query' => array(array('key' => 'language_code', 'value' => $field['value'])));
            $langaugesQuery = new WP_Query($languagesQueryArgs);
            // Do the loop
            while ($langaugesQuery->have_posts()) : $langaugesQuery->the_post();
                $langauge = $langaugesQuery->post;
                $doc_language = get_post_meta($langauge->ID, 'language_name', true );
            endwhile; wp_reset_postdata();
            return $doc_language;
        }
        elseif($field['name'] == 'cj_currency') {
            $doc_currency = '';
            /*
             * Load Urgency
             */
            $currenciesQueryArgs = array('numberposts' => -1,  'post_status' => 'any', 'post_type' => 'content_currencies',
                'meta_query' => array(array('key' => 'currency_code', 'value' => $field['value'])));
            $currenciesQuery = new WP_Query($currenciesQueryArgs);
            // Do the loop
            while ($currenciesQuery->have_posts()) : $currenciesQuery->the_post();
                $currency = $currenciesQuery->post;
                $doc_currency = get_post_meta($currency->ID, 'currency_name', true );
            endwhile; wp_reset_postdata();
            return $doc_currency;
        }
        elseif($field['name'] == 'cj_term') {
            $doc_urgency = '';
            /*
             * Load Urgency
             */
            $urgencyQueryArgs = array('numberposts' => -1,  'post_status' => 'any', 'post_type' => 'content_urgency',
                'meta_query' => array(array('key' => 'urgency_code', 'value' => $field['value'])));
            $urgencyQuery = new WP_Query($urgencyQueryArgs);
            // Do the loop
            while ($urgencyQuery->have_posts()) : $urgencyQuery->the_post();
                $urgency = $urgencyQuery->post;
                $doc_urgency = get_post_meta($urgency->ID, 'urgency_name', true );
            endwhile; wp_reset_postdata();
            return $doc_urgency;
        }
        else {
            return $field['value'];
        }

    }


    /*------------------------------------------------------------------------------
    Save the new Custom Fields values
    INPUT:
        $post_id (int) id of the post these custom fields are associated with
        $post (obj) the post object
  ------------------------------------------------------------------------------*/
    public static function save_custom_fields( $post_id, $post ) 
    {
        if ( $post->post_type == 'content_jobs') {
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