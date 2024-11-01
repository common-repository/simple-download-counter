<?php // Simple Download Counter - Reset Options

if (!defined('ABSPATH')) exit;

function simple_download_counter_admin_notices() {
			
	$screen_id = simple_download_counter_get_current_screen_id();
	
	if ($screen_id === 'sdc_download_page_download-counter-settings') {
		
		if (isset($_GET['download-counter-reset-options'])) {
			
			if ($_GET['download-counter-reset-options'] === 'true') : ?>
				
				<div class="notice notice-success is-dismissible"><p><strong><?php esc_html_e('Default options restored.', 'simple-download-counter'); ?></strong></p></div>
				
			<?php else : ?>
				
				<div class="notice notice-info is-dismissible"><p><strong><?php esc_html_e('No changes made to options.', 'simple-download-counter'); ?></strong></p></div>
				
			<?php endif;
			
		}
		
		if (!simple_download_counter_check_date_expired() && !simple_download_counter_dismiss_notice_check()) {
			
			?>
			
			<div class="notice notice-success notice-margin">
				<p>
					<strong><?php esc_html_e('Fall Sale!', 'simple-download-counter'); ?></strong> 
					<?php esc_html_e('Take 25% OFF any of our', 'simple-download-counter'); ?> 
					<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/"><?php esc_html_e('Pro WordPress plugins', 'simple-download-counter'); ?></a> 
					<?php esc_html_e('and', 'simple-download-counter'); ?> 
					<a target="_blank" rel="noopener noreferrer" href="https://books.perishablepress.com/"><?php esc_html_e('books', 'simple-download-counter'); ?></a>. 
					<?php esc_html_e('Apply code', 'simple-download-counter'); ?> <code>FALL2024</code> <?php esc_html_e('at checkout. Sale ends 12/21/24.', 'simple-download-counter'); ?> 
					<?php echo simple_download_counter_dismiss_notice_link(); ?>
				</p>
			</div>
			
			<?php
			
		}
		
	}
	
}

//

function simple_download_counter_dismiss_notice_activate() {
	
	delete_option('simple-download-counter-dismiss-notice');
	
}

function simple_download_counter_dismiss_notice_version() {
	
	$version_current = DOWNLOAD_COUNTER_VERSION;
	
	$version_previous = get_option('simple-download-counter-dismiss-notice');
	
	$version_previous = ($version_previous) ? $version_previous : $version_current;
	
	if (version_compare($version_current, $version_previous, '>')) {
		
		delete_option('simple-download-counter-dismiss-notice');
		
	}
	
}

function simple_download_counter_dismiss_notice_check() {
	
	$check = get_option('simple-download-counter-dismiss-notice');
	
	return ($check) ? true : false;
	
}

function simple_download_counter_dismiss_notice_save() {
	
	if (isset($_GET['dismiss-notice-verify']) && wp_verify_nonce($_GET['dismiss-notice-verify'], 'simple_download_counter_dismiss_notice')) {
		
		if (!current_user_can('manage_options')) exit;
		
		$result = update_option('simple-download-counter-dismiss-notice', DOWNLOAD_COUNTER_VERSION, false);
		
		$result = $result ? 'true' : 'false';
		
		$location = admin_url('edit.php?post_type=sdc_download&page=download-counter-settings&dismiss-notice='. $result);
		
		wp_redirect($location);
		
		exit;
		
	}
	
}

function simple_download_counter_dismiss_notice_link() {
	
	$nonce = wp_create_nonce('simple_download_counter_dismiss_notice');
	
	$href  = add_query_arg(array('dismiss-notice-verify' => $nonce), admin_url('edit.php?post_type=sdc_download&page=download-counter-settings'));
	
	$label = esc_html__('Dismiss', 'simple-download-counter');
	
	return '<a class="sdc-dismiss-notice" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}

function simple_download_counter_check_date_expired() {
	
	$expires = apply_filters('simple_download_counter_check_date_expired', '2024-12-21');
	
	return (new DateTime() > new DateTime($expires)) ? true : false;
	
}

//

function simple_download_counter_reset_options() {
	
	if (isset($_GET['download-counter-reset-options']) && wp_verify_nonce($_GET['download-counter-reset-options'], 'download-counter-reset-options')) {
		
		if (!current_user_can('manage_options')) exit;
		
		$update = delete_option('download_counter_options');
		
		if ($update) set_transient('update_sdc_download_permalinks', true);
		
		$result = $update ? 'true' : 'false';
		
		$location = add_query_arg(array('download-counter-reset-options' => $result), admin_url('edit.php?post_type=sdc_download&page=download-counter-settings'));
		
		wp_redirect($location);
		
		exit;
		
	}
	
}
