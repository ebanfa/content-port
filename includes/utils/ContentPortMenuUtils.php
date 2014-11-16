<?php
class ContentPortMenuUtils {

	// Used to uniquely identify this plugin's menu page in the WP manager
	const admin_menu_slug = 'content_port';

	/**
	 * Create Content Port plugin admin menu
	 */
	public static function create_admin_menu()
	{
	 	add_menu_page('Content Port', 'Content Port', 'manage_options',	self::admin_menu_slug, 'ContentPortMenuUtils::get_admin_page');
	 	self::add_plugin_admin_submenus();
	 	self::remove_toplevel_cpt_menus();
	}

	/**
	 * Create Content Port plugin admin sub menus
	 */
	public static function add_plugin_admin_submenus() {

	 	// Add Orders CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Orders', 'Orders', 'manage_options', 
	 		self::admin_menu_slug . '_show_jobs', 'ContentPortMenuUtils::content_shop_render_jobs');
	 	// Add Orders CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Writers', 'Writers', 'manage_options', 
	 		self::admin_menu_slug . '_show_writers', 'ContentPortMenuUtils::content_shop_render_writers');
	 	// Add Orders CPT sub menu
	 	/*add_submenu_page( self::admin_menu_slug , 'Content', 'Content', 'manage_options',
	 		self::admin_menu_slug . '_show_jcontent', 'ContentPortMenuUtils::content_shop_render_content');*/
	 	// Add Questions CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Questions', 'Questions', 'manage_options', 
	 		self::admin_menu_slug . '_show_jquestions', 'ContentPortMenuUtils::content_shop_render_questions');


	 	/*
	 	// Add Orders CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Activities', 'Activities', 'manage_options', 
	 		self::admin_menu_slug . '_show_jactivities', 'ContentPortMenuUtils::content_shop_render_activities');

	 	// Add Orders CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Content Writing', 'Content Writing', 'manage_options', 
	 		self::admin_menu_slug . '_show_writing_settings', 'ContentPortMenuUtils::content_shop_show_writing_settings');

	 	/*	// Add Country CPT sub menu
	 	add_submenu_page(self::admin_menu_slug . '_show_writing_settings' , 'Countries', 'Countries', 'manage_options', 
	 		self::admin_menu_slug . '_show_countries', 'ContentPortMenuUtils::content_shop_render_countries');
	 	// Add Document Type CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Document Types', 'Document Types', 'manage_options', 
	 		self::admin_menu_slug . '_show_doctypes', 'ContentPortMenuUtils::content_shop_render_doctypes');
	 	// Add Subject Area CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Subject Areas', 'Subject Areas', 'manage_options', 
	 		self::admin_menu_slug . '_show_subjectareas', 'ContentPortMenuUtils::content_shop_render_subjectareas');
	 	// Add Number Of Page CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'No Of Pages', 'No Of Pages', 'manage_options', 
	 		self::admin_menu_slug . '_show_noofpages', 'ContentPortMenuUtils::content_shop_render_noofpages');
	 	// Add Urgency CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Urgency', 'Urgency', 'manage_options', 
	 		self::admin_menu_slug . '_show_urgency', 'ContentPortMenuUtils::content_shop_render_urgency');
	 	// Add Academic Level CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Academic Levels', 'Academic Levels', 'manage_options', 
	 		self::admin_menu_slug . '_show_academiclevels', 'ContentPortMenuUtils::content_shop_render_academiclevels');
	 	// Add Currency CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Currencies', 'Currencies', 'manage_options', 
	 		self::admin_menu_slug . '_show_currencies', 'ContentPortMenuUtils::content_shop_render_currencies');
	 	// Add Language CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Languages', 'Languages', 'manage_options', 
	 		self::admin_menu_slug . '_show_languages', 'ContentPortMenuUtils::content_shop_render_languages');
	 	// Add Style CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Styles', 'Styles', 'manage_options', 
	 		self::admin_menu_slug . '_show_styles', 'ContentPortMenuUtils::content_shop_render_styles');*/
		// Add Currency CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Currencies', 'Currencies', 'manage_options', 
	 		self::admin_menu_slug . '_show_currencies', 'ContentPortMenuUtils::content_shop_render_currencies');
	}

	/**
	 * Remove Content Port plugin CPT default menus
	 */
	public static function remove_toplevel_cpt_menus() {
		remove_menu_page( 'edit.php?post_type=content_jobs');
		remove_menu_page( 'edit.php?post_type=content_writer');
		remove_menu_page( 'edit.php?post_type=content_questions');
		remove_menu_page( 'edit.php?post_type=content_doc');
		remove_menu_page( 'edit.php?post_type=content_activity');
		remove_menu_page( 'edit.php?post_type=content_countries');
		remove_menu_page( 'edit.php?post_type=content_doctypes');
		remove_menu_page( 'edit.php?post_type=content_subjects');
		remove_menu_page( 'edit.php?post_type=content_noofpages');
		remove_menu_page( 'edit.php?post_type=content_urgency');
		remove_menu_page( 'edit.php?post_type=content_acadlevels');
		remove_menu_page( 'edit.php?post_type=content_currencies');
		remove_menu_page( 'edit.php?post_type=content_languages');
		remove_menu_page( 'edit.php?post_type=content_styles');
	}

	/**
	 * Adds a link to the settings directly from the plugins page.  This filter is 
	 * called for each plugin, so we need to make sure we only alter the links that
	 * are displayed for THIS plugin.
	 *
	 * The inputs here come directly from WordPress:
	 * @param	array	$links - a hash in theformat of name => translation e.g.
	 *		array('deactivate' => 'Deactivate') that describes all links available to a plugin.
	 * @param	string	$file 	- the path to plugin's main file (the one with the info header), 
	 *		relative to the plugins directory, e.g. 'content-chunks/index.php'
	 * @return	array 	The $links hash.
	 */
	public static function add_plugin_settings_link($links, $file)
	{
		$settings_link = sprintf('<a href="%s">%s</a>'
			, admin_url( 'options-general.php?page='.self::admin_menu_slug )
			, 'Settings'
		);
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
 	 * Prints the administration page for this plugin.
	 */
	public static function get_admin_page()
	{
		if ( !empty($_POST) && check_admin_referer('cp_port_options_update','cp_port_admin_nonce') )
		{
			update_option( 'cp_paypal_url', stripslashes($_POST['cp_paypal']) );
			update_option( 'cp_paypal_id', stripslashes($_POST['cp_paypal_id']) );
			update_option( 'cp_paypal_return', stripslashes($_POST['cp_paypal_return']) );
			update_option( 'cp_paypal_cancel', stripslashes($_POST['cp_paypal_cancel']) );
			update_option( 'cp_notify_orders', stripslashes($_POST['cp_notify_orders']) );
			update_option( 'cp_notify_accounts', stripslashes($_POST['cp_notify_accounts']) );
			update_option( 'cp_slider_id', stripslashes($_POST['cp_slider_id']) );
			$msg = '<div class="updated"><p>Your settings have been <strong>updated</strong></p></div>';
		}
		//"Short code here"; //esc_attr( get_option(self::option_key, self::default_shortcode_name) );
		include('admin_page.php');
	}

	/**
	 * Create Orders post-type sub menu
	 */
	public static function content_shop_render_activities()
	{
		$url = admin_url().'edit.php?post_type=content_activity';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
	/**
	 * Create Orders post-type sub menu
	 */
	public static function content_shop_render_content()
	{
		$url = admin_url().'edit.php?post_type=content_doc';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Questions post-type sub menu
	 */
	public static function content_shop_render_questions()
	{
		$url = admin_url().'edit.php?post_type=content_questions';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Orders post-type sub menu
	 */
	public static function content_shop_render_jobs()
	{
		$url = admin_url().'edit.php?post_type=content_jobs';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Writer post-type sub menu
	 */
	public static function content_shop_render_writers()
	{
		$url = admin_url().'edit.php?post_type=content_writer';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Country post-type sub menu
	 */
	public static function content_shop_render_countries()
	{
		$url = admin_url().'edit.php?post_type=content_countries';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Country post-type sub menu
	 */
	public static function content_shop_show_writing_settings()
	{
		$url = admin_url().'edit.php?post_type=content_countries';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Document Type post-type sub menu
	 */
	public static function content_shop_render_doctypes()
	{
		$url = admin_url().'edit.php?post_type=content_doctypes';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Subject Areas post-type sub menu
	 */
	public static function content_shop_render_subjectareas()
	{
		$url = admin_url().'edit.php?post_type=content_subjects';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Number Of Pages post-type sub menu
	 */
	public static function content_shop_render_noofpages()
	{
		$url = admin_url().'edit.php?post_type=content_noofpages';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Urgency post-type sub menu
	 */
	public static function content_shop_render_urgency()
	{
		$url = admin_url().'edit.php?post_type=content_urgency';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Academic Level post-type sub menu
	 */
	public static function content_shop_render_academiclevels()
	{
		$url = admin_url().'edit.php?post_type=content_acadlevels';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Currency post-type sub menu
	 */
	public static function content_shop_render_currencies()
	{
		$url = admin_url().'edit.php?post_type=content_currencies';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Language post-type sub menu
	 */
	public static function content_shop_render_languages()
	{
		$url = admin_url().'edit.php?post_type=content_languages';
		?>
		<script>location.href='<?php echo $url;?>';</script>
		<?php
	}

	/**
	 * Create Style post-type sub menu
	 */
	public static function content_shop_render_styles()
	{
		$url = admin_url().'edit.php?post_type=content_styles';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
}
?>