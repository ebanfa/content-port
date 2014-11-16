<?php
/*
Plugin Name: Content Port
Plugin URI: http://wordpress.org/plugins/content-port/
Description: This plugin implements the functionality required an content creation and ordering system. 
Author: Edward Banfa
Version: 0.1
Author URI: 
*/

// include() or require() any necessary files here...
include_once('includes/ContentPort.php');
include_once('includes/content/ContentCPT.php');
include_once('includes/content-job/ContentJobCPT.php');
include_once('includes/content-writer/ContentWriterCPT.php');
include_once('includes/content-user/ContentUsersUtils.php');
include_once('includes/content-order/ContentOrderUtils.php');
include_once('includes/content-order/ContentJobOrderCPT.php');
include_once('includes/content-order/ContentJobOrderItemCPT.php');
include_once('includes/content-user/TimelineActivityCPT.php');
include_once('includes/content-question/ContentQuestionCPT.php');
include_once('includes/utils/CustomPostTypesUtils.php');
include_once('includes/utils/ContentPortMenuUtils.php');

/* -------------- ContentPort Plugin setup actions---------- */
// Construct the plugin on WP load.
add_action('init', 'ContentPort::initialize');
// Add custom meta boxes
add_action('admin_menu', 'CustomPostTypesUtils::create_meta_box' );
// Register new post type 'content_order'
add_action('init', 'CustomPostTypesUtils::register_custom_post_type');
// Register action to save custom fields
add_action('save_post', 'CustomPostTypesUtils::save_custom_fields', 1, 2 );
// Remove WordPress default custom field handling
add_action('do_meta_boxes', 'CustomPostTypesUtils::remove_default_custom_fields', 10, 3 );

// Register action to create the admin menu
add_action('admin_menu', 'ContentPortMenuUtils::create_admin_menu');
// Register action to create the admin settings page link
add_filter('plugin_action_links_essay-shop/index.php', 'ContentPortMenuUtils::add_plugin_settings_link', 10, 2 );

// Customize the list page for CPT's
add_filter('manage_content_jobs_posts_columns', 'ContentJobCPT::content_jobs_table_head');
add_filter('manage_content_writer_posts_columns', 'ContentWriterCPT::content_writer_table_head');

add_action('manage_content_jobs_posts_custom_column', 'ContentJobCPT::content_jobs_table_content', 10, 2 );
add_action('manage_content_writer_posts_custom_column', 'ContentWriterCPT::content_writer_table_content', 10, 2 );

add_filter('manage_edit-content_jobs_sortable_columns', 'ContentJobCPT::content_jobs_table_sorting');
add_filter('manage_edit-content_writer_sortable_columns', 'ContentWriterCPT::content_writer_table_sorting');

add_filter('request', 'ContentJobCPT::content_jobs_columns_orderby' );
add_filter('request', 'ContentWriterCPT::content_writer_columns_orderby' );

// Content user related actions
add_action('add_content_port_user', 'ContentUsersUtils::add_user', 10, 1);

/* -------------- ContentPort Session filters --------------- 
add_action('init', 'startContentPortperSession', 1);
add_action('wp_logout', 'endContentPortperSession');
add_action('wp_login', 'endContentPortperSession');*/

/*function startContentPortperSession() {
    if(!session_id()) {
        session_start();
    }
}

function endContentPortperSession() {
    session_destroy ();
}*/

/* -------------- ContentPort Paypal actions  ------------- */
add_action('paypal-web_accept', 'ContentOrderUtils::processPaypalWebAcceptPayment');
add_action('send_order_created_email', 'ContentOrderUtils::sendOrderCreatedEmail', 10, 1);

/* -------------- User created email actions  ------------- */
add_action('send_user_created_email', 'ContentOrderUtils::sendUserCreatedEmail', 10, 2);


/* -------------- ContentPort Email filters --------------- */
add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');
add_filter( 'wp_mail_content_type', 'set_html_content_type' );

function new_mail_from($old) {
 return get_option('cp_notify_orders');
}

function new_mail_from_name($old) {
 return get_bloginfo('name');
}

function set_html_content_type() {
return 'text/html';
}


/*EOF*/