<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Content Port Administration</h2>
	
	<?php print $msg; ?>

	<h3><label for="shortcode_name">Admin Credentials</label></h3>
	<form action="" method="post" id="essay_orders_form">
		<label for="cp_paypal">PayPal URL</label>		
		<input id="cp_paypal" name="cp_paypal" type="text" size="50" value="<?php echo get_option('cp_paypal_url');?>" /><br><br>

		<label for="cp_paypal_id">PayPal Business ID</label>		
		<input id="cp_paypal_id" name="cp_paypal_id" type="text" size="50" value="<?php echo get_option('cp_paypal_id');?>" /><br><br>

		<label for="cp_paypal_return">PayPal Cancel URL</label>		
		<input id="cp_paypal_return" name="cp_paypal_return" type="text" size="50" value="<?php echo get_option('cp_paypal_return');?>" /><br><br>

		<label for="cp_paypal_cancel">PayPal Cancel Return URL</label>		
		<input id="cp_paypal_cancel" name="cp_paypal_cancel" type="text" size="50" value="<?php echo get_option('cp_paypal_cancel');?>" /><br><br>

		<label for="cp_notify_orders">Notify Email Orders</label>		
		<input id="cp_notify_orders" name="cp_notify_orders" type="text" size="50" value="<?php echo get_option('cp_notify_orders');?>" /><br><br>

		<label for="cp_notify_accounts">Notify Email Accounts</label>		
		<input id="cp_notify_accounts" name="cp_notify_accounts" type="text" size="50" value="<?php echo get_option('cp_notify_accounts');?>" /><br><br>

		<label for="cp_slider_id">Slider ID</label>		
		<input id="cp_slider_id" name="cp_slider_id" type="text" size="50" value="<?php echo get_option('cp_slider_id');?>" />
		<p class="submit">
			<input type="submit" name="submit" value="Update" />
		</p>
		<?php wp_nonce_field('cp_port_options_update','cp_port_admin_nonce'); ?>
		
	</form>	
</div>