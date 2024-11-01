<?php // Simple Download Counter - Enqueue

if (!defined('ABSPATH')) exit;


function simple_download_counter_admin_enqueue_scripts($hook) {
	
	if (is_null($hook)) return;
	
	if ($hook === 'edit.php' || $hook === 'post.php' || $hook === 'post-new.php' || $hook === 'sdc_download_page_download-counter-settings') {
		
		wp_enqueue_style('wp-jquery-ui-dialog');
		
		wp_enqueue_style('download-counter', DOWNLOAD_COUNTER_URL .'css/settings.css', array(), DOWNLOAD_COUNTER_VERSION);
		
		wp_enqueue_script('download-counter', DOWNLOAD_COUNTER_URL .'js/settings.js', array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'), DOWNLOAD_COUNTER_VERSION);
		
		simple_download_counter_wp_localize_script();
		
	}
	
}


function simple_download_counter_wp_localize_script() {
	
	$url = plugin_dir_url(__DIR__) .'img/download.png';
	
	$clipboard = __('Copied!', 'simple-download-counter');
	
	$array = array(
		
		'url' => $url,
		'clipboard' => $clipboard,
		
	);
	
	wp_localize_script('download-counter', 'download_counter', $array);
	
}


function simple_download_counter_admin_print_scripts() {
	
	if (simple_download_counter_get_current_screen_id() === 'sdc_download_page_download-counter-settings') : 
	
	?>
	
	<script>
		var 
		download_counter_reset_title   = '<?php _e('Confirm Reset',            'simple-download-counter'); ?>',
		download_counter_reset_message = '<?php _e('Restore default options?', 'simple-download-counter'); ?>',
		download_counter_reset_true    = '<?php _e('Yes, make it so.',         'simple-download-counter'); ?>',
		download_counter_reset_false   = '<?php _e('No, abort mission.',       'simple-download-counter'); ?>';
	</script>
	
	<?php endif;
	
}
