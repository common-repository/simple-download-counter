<?php // Simple Download Counter - Validate Settings

if (!defined('ABSPATH')) exit;


function simple_download_counter_validate_settings($input) {
	
	$options = simple_download_counter_options();
	
	$download_key = isset($options['download_key']) ? $options['download_key'] : '';
	
	if (isset($input['download_key'])) {
		
		if (!preg_match("/^[a-z0-9_-]+$/", $input['download_key'])) {
			
			$input['download_key'] = $download_key;
			
		}
		
	}
	
	if (!isset($input['custom_fields'])) $input['custom_fields'] = null;
	$input['custom_fields'] = ($input['custom_fields'] == 1 ? 1 : 0);
	
	return $input;
	
}