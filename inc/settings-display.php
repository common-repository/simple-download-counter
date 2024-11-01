<?php // Simple Download Counter - Display Settings

if (!defined('ABSPATH')) exit;


function simple_download_counter_menu_pages() {
	
	// add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback, $position)
	
	add_submenu_page(
		
		'edit.php?post_type=sdc_download', 
		esc_html__('Settings', 'simple-download-counter'), 
		esc_html__('Settings', 'simple-download-counter'), 
		'manage_options', 'download-counter-settings', 
		'simple_download_counter_display_settings'
		
	);
	
}


function simple_download_counter_display_settings() {
	
	?>
	
	<div class="wrap">
		<h1><span class="dashicons dashicons-download"></span> <?php echo DOWNLOAD_COUNTER_NAME; ?> <span class="download-counter-version"><?php echo DOWNLOAD_COUNTER_VERSION; ?></span></h1>
		
		<?php settings_errors(); ?>
		
		<form method="post" action="options.php">
			
			<?php 
				settings_fields('download_counter_options');
				do_settings_sections('download_counter_options');
				submit_button();
			?>
			
		</form>
	</div>
	
	<?php 
	
}