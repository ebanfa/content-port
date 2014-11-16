<?php
/*------------------------------------------------------------------------------
This plugin standardizes the custom fields for specified content types, e.g.
post, page, and any other custom post-type you register via a plugin.

TO-DO: 
	Create a options page and a menu item
	read the $prefix from the database (? -- maybe not... changing it after posts
		have been created would be disasterous)
	read the $content_types_array from the database
	read the $custom_fields from the database
	more form element types?  E.g. date?
------------------------------------------------------------------------------*/

require('content-order/ContentOrderCPT.php');

class ContentPort {

	public static $date_format = 'M j, Y, H:i';
	
    /**
     * Construct the plugin object
     */
    public static function initialize()
    {
    	// Register the plugin css
    	self::register_css();
    }

    /**
     * Register the plugin css
     */
    public static function register_css()
    {
        /*$src = plugins_url('css/essay-shop.css',dirname(__FILE__) );
        wp_register_style('essay-shop', $src);
        wp_enqueue_style('essay-shop');*/
    }

	

} // End Class
/*EOF*/