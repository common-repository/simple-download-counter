<?php // Simple Download Counter - Register Settings

if (!defined('ABSPATH')) exit;


function simple_download_counter_register_settings() {
	
	// register_setting( $option_group, $option_name, $sanitize_callback );
	// add_settings_section( $id, $title, $callback, $page ); 
	// add_settings_field( $id, $title, $callback, $page, $section, $args );
	
	register_setting('download_counter_options', 'download_counter_options', 'simple_download_counter_validate_settings');
	
	add_settings_section('settings', __('Plugin Settings', 'simple-download-counter'), 'simple_download_counter_callback_settings', 'download_counter_options');
	
	add_settings_field('download_key',  __('Download Key',  'simple-download-counter'), 'simple_download_counter_callback_text',          'download_counter_options', 'settings', array('id' => 'download_key',  'section' => 'settings', 'label' => esc_html__('Alphanumeric string for download URLs. Default value is recommended.', 'simple-download-counter')));
	
	add_settings_field('reset_options', __('Reset Options', 'simple-download-counter'), 'simple_download_counter_callback_reset_options', 'download_counter_options', 'settings', array('id' => 'reset_options', 'section' => 'settings', 'label' => esc_html__('Restore default plugin options', 'simple-download-counter')));
	
	add_settings_field('rate_plugin',   __('Rate Plugin',   'simple-download-counter'), 'simple_download_counter_callback_rate',          'download_counter_options', 'settings', array('id' => 'rate_plugin',   'section' => 'settings', 'label' => esc_html__('Show support with a 5-star rating &raquo;', 'simple-download-counter')));
	
	add_settings_field('show_support',  __('Show Support',  'simple-download-counter'), 'simple_download_counter_callback_support',       'download_counter_options', 'settings', array('id' => 'show_support',  'section' => 'settings', 'label' => esc_html__('Show support with a small donation &raquo;', 'simple-download-counter')));
	
	// add_settings_field('custom_fields', __('Custom Fields', 'simple-download-counter'), 'simple_download_counter_callback_checkbox',      'download_counter_options', 'settings', array('id' => 'custom_fields', 'section' => 'settings', 'label' => esc_html__('Display custom fields on Download posts. Default value is recommended.', 'simple-download-counter')));
	
}