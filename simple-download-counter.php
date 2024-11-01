<?php 
/*
	Plugin Name: Simple Download Counter
	Plugin URI: https://perishablepress.com/simple-download-counter/
	Description: Counts the number of downloads for any file type. Display download counts using shortcodes.
	Tags: download counter, download manager, file manager, downloads, statistics
	Author: Jeff Starr
	Author URI: https://plugin-planet.com/
	Donate link: https://monzillamedia.com/donate.html
	Contributors: specialk
	Requires at least: 5.0
	Tested up to: 6.7
	Stable tag: 2.0
	Version:    2.0
	Requires PHP: 5.6.20
	Text Domain: simple-download-counter
	Domain Path: /languages
	License: GPL v2 or later
*/

/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 
	2 of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	with this program. If not, visit: https://www.gnu.org/licenses/
	
	Copyright 2024 Monzilla Media. All rights reserved.
*/

if (!defined('ABSPATH')) die();

if (!class_exists('Simple_Download_Counter')) {
	
	class Simple_Download_Counter {
		
		function __construct() {
			
			$this->constants();
			$this->includes();
			
			register_activation_hook  (__FILE__, 'simple_download_counter_flush_rewrite_rules');
			register_deactivation_hook(__FILE__, 'simple_download_counter_flush_rewrite_rules');
			register_activation_hook  (__FILE__, 'simple_download_counter_dismiss_notice_activate');
			add_action('pre_update_option_download_counter_options', 'simple_download_counter_updated_option', 10, 3);
			add_action('init', 'simple_download_counter_updated_option_flush');
			
			add_action('admin_init',          array($this, 'check_version'));
			add_action('plugins_loaded',      array($this, 'load_i18n'));
			add_filter('plugin_action_links', array($this, 'action_links'), 10, 2);
			add_filter('plugin_row_meta',     array($this, 'plugin_links'), 10, 2);
			add_filter('admin_footer_text',   array($this, 'footer_text'),  10, 1);
			
			add_action('admin_init',            'simple_download_counter_register_settings');
			add_action('admin_init',            'simple_download_counter_reset_options');
			add_action('admin_notices',         'simple_download_counter_admin_notices');
			add_action('current_screen',        'simple_download_counter_settings_flush_rewrite');
			add_action('admin_enqueue_scripts', 'simple_download_counter_admin_enqueue_scripts');
			add_action('admin_print_scripts',   'simple_download_counter_admin_print_scripts');
			add_action('admin_menu',            'simple_download_counter_menu_pages');
			add_action('admin_init',            'simple_download_counter_dismiss_notice_save');
			add_action('admin_init',            'simple_download_counter_dismiss_notice_version');
			
			add_action('init', 'simple_download_counter_post_type');
			add_action('init', 'simple_download_counter_custom_category', 0);
			add_action('init', 'simple_download_counter_custom_tag', 0);
			
			add_filter('manage_sdc_download_posts_columns',         'simple_download_counter_columns', 10, 2);
			add_filter('manage_edit-sdc_download_sortable_columns', 'simple_download_counter_columns_sort');
			add_action('pre_get_posts',                             'simple_download_counter_columns_count');
			add_action('manage_sdc_download_posts_custom_column',   'simple_download_counter_columns_data', 10, 2);
			add_action('restrict_manage_posts',                     'simple_download_counter_columns_category', 10, 2);
			add_filter('post_row_actions',                          'simple_download_counter_post_row_actions', 10, 1);
			
			add_action('current_screen',             'simple_download_counter_add_mce');
			add_filter('tiny_mce_before_init',       'simple_download_counter_editor_height');
			add_action('admin_print_footer_scripts', 'simple_download_counter_add_quicktags');
			
			add_filter('enter_title_here',      'simple_download_counter_enter_title_here');
			add_filter('post_updated_messages', 'simple_download_counter_post_updated_messages');
			add_action('admin_head',            'simple_download_counter_hide_preview_button');
			
			add_action('add_meta_boxes',            'simple_download_counter_add_meta_box',    10, 2);
			add_action('add_meta_boxes',            'simple_download_counter_remove_meta_box', 10, 2);
			add_filter('default_hidden_meta_boxes', 'simple_download_counter_hide_meta_box',   10, 2);
			add_action('save_post',                 'simple_download_counter_meta_box_process');
			
			add_action('parse_request', 'simple_download_counter_download_handler');
			add_action('init',          'simple_download_counter_add_rewrite', 0);
			
			add_shortcode('sdc_download',            'simple_download_counter_download');
			add_shortcode('sdc_downloads_published', 'simple_download_counter_downloads_published');
			add_shortcode('sdc_count',               'simple_download_counter_count');
			add_shortcode('sdc_count_total',         'simple_download_counter_count_total');
			
			add_shortcode('sdc_meta',                'simple_download_counter_meta');
			
		} 
		
		function constants() {
			
			if (!defined('DOWNLOAD_COUNTER_VERSION')) define('DOWNLOAD_COUNTER_VERSION', '2.0');
			if (!defined('DOWNLOAD_COUNTER_REQUIRE')) define('DOWNLOAD_COUNTER_REQUIRE', '5.0');
			if (!defined('DOWNLOAD_COUNTER_TESTED'))  define('DOWNLOAD_COUNTER_TESTED',  '6.7');
			if (!defined('DOWNLOAD_COUNTER_AUTHOR'))  define('DOWNLOAD_COUNTER_AUTHOR',  'Jeff Starr');
			if (!defined('DOWNLOAD_COUNTER_NAME'))    define('DOWNLOAD_COUNTER_NAME',    __('Simple Download Counter', 'simple-download-counter'));
			if (!defined('DOWNLOAD_COUNTER_HOME'))    define('DOWNLOAD_COUNTER_HOME',    esc_url('https://perishablepress.com/simple-download-counter/'));
			if (!defined('DOWNLOAD_COUNTER_URL'))     define('DOWNLOAD_COUNTER_URL',     plugin_dir_url(__FILE__));
			if (!defined('DOWNLOAD_COUNTER_DIR'))     define('DOWNLOAD_COUNTER_DIR',     plugin_dir_path(__FILE__));
			if (!defined('DOWNLOAD_COUNTER_FILE'))    define('DOWNLOAD_COUNTER_FILE',    plugin_basename(__FILE__));
			if (!defined('DOWNLOAD_COUNTER_SLUG'))    define('DOWNLOAD_COUNTER_SLUG',    basename(dirname(__FILE__)));
			
		}
		
		function includes() {
			
			require_once DOWNLOAD_COUNTER_DIR .'inc/functions-admin.php';
			require_once DOWNLOAD_COUNTER_DIR .'inc/functions-core.php';
			require_once DOWNLOAD_COUNTER_DIR .'inc/functions-shortcode.php';
			require_once DOWNLOAD_COUNTER_DIR .'inc/register-post-type.php';
			
			if (is_admin()) {
				
				require_once DOWNLOAD_COUNTER_DIR .'inc/resources-enqueue.php';
				require_once DOWNLOAD_COUNTER_DIR .'inc/settings-callbacks.php';
				require_once DOWNLOAD_COUNTER_DIR .'inc/settings-display.php';
				require_once DOWNLOAD_COUNTER_DIR .'inc/settings-register.php';
				require_once DOWNLOAD_COUNTER_DIR .'inc/settings-reset.php';
				require_once DOWNLOAD_COUNTER_DIR .'inc/settings-validate.php';
				
			}
			
		}
		
		function options() {
			
			return array(
				
				'download_key'  => 'sdc_download',
				'custom_fields' => false,
				
			);
			
		}
		
		function action_links($links, $file) {
			
			if ($file === DOWNLOAD_COUNTER_FILE) {
				
				$settings = '<a href="'. admin_url('edit.php?post_type=sdc_download&page=download-counter-settings') .'">'. esc_html__('Settings', 'simple-download-counter') .'</a>';
				
				array_unshift($links, $settings);
				
			}
			
			return $links;
			
		}
		
		function plugin_links($links, $file) {
			
			if ($file === DOWNLOAD_COUNTER_FILE) {
				
				$rate_href  = 'https://wordpress.org/support/plugin/simple-download-counter/reviews/?rate=5#new-post';
				$rate_title = esc_attr__('Click here to rate and review this plugin on WordPress.org', 'simple-download-counter');
				$rate_text  = esc_html__('Rate this plugin', 'simple-download-counter') .'&nbsp;&raquo;';
				
				$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $rate_href .'" title="'. $rate_title .'">'. $rate_text .'</a>';
				
			}
			
			return $links;
			
		}
		
		function footer_text($text) {
			
			$screen_id = simple_download_counter_get_current_screen_id();
			
			$ids = array('sdc_download_page_download-counter-settings');
			
			if ($screen_id && apply_filters('simple_download_counter_admin_footer_text', in_array($screen_id, $ids))) {
				
				$text = __('Like this plugin? Give it a', 'simple-download-counter');
				
				$text .= ' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/simple-download-counter/reviews/?rate=5#new-post">';
				
				$text .= __('★★★★★ rating&nbsp;&raquo;', 'simple-download-counter') .'</a>';
				
			}
			
			return $text;
			
		}
		
		function check_version() {
			
			$wp_version = get_bloginfo('version');
			
			if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
				
				if (version_compare($wp_version, DOWNLOAD_COUNTER_REQUIRE, '<')) {
					
					if (is_plugin_active(DOWNLOAD_COUNTER_FILE)) {
						
						deactivate_plugins(DOWNLOAD_COUNTER_FILE);
						
						$msg  = '<strong>'. DOWNLOAD_COUNTER_NAME .'</strong> '. esc_html__('requires WordPress ', 'simple-download-counter') . DOWNLOAD_COUNTER_REQUIRE;
						$msg .= esc_html__(' or higher, and has been deactivated! ', 'simple-download-counter');
						$msg .= esc_html__('Please return to the', 'simple-download-counter') .' <a href="'. admin_url() .'">';
						$msg .= esc_html__('WP Admin Area', 'simple-download-counter') .'</a> '. esc_html__('to upgrade WordPress and try again.', 'simple-download-counter');
						
						wp_die($msg);
						
					}
					
				}
				
			}
			
		}
		
		function load_i18n() {
			
			$domain = 'simple-download-counter';
			
			$locale = apply_filters('download_counter_locale', get_locale(), $domain);
			
			$dir    = trailingslashit(WP_LANG_DIR);
			
			$file   = $domain .'-'. $locale .'.mo';
			
			$path_1 = $dir . $file;
			
			$path_2 = $dir . $domain .'/'. $file;
			
			$path_3 = $dir .'plugins/'. $file;
			
			$path_4 = $dir .'plugins/'. $domain .'/'. $file;
			
			$paths = array($path_1, $path_2, $path_3, $path_4);
			
			foreach ($paths as $path) {
				
				if ($loaded = load_textdomain($domain, $path)) {
					
					return $loaded;
					
				} else {
					
					return load_plugin_textdomain($domain, false, dirname(DOWNLOAD_COUNTER_FILE) .'/languages/');
					
				}
				
			}
			
		}
		
		function __clone() {
			
			_doing_it_wrong(__FUNCTION__, esc_html__('Sorry, pal!', 'simple-download-counter'), DOWNLOAD_COUNTER_VERSION);
			
		}
		
		function __wakeup() {
			
			_doing_it_wrong(__FUNCTION__, esc_html__('Sorry, pal!', 'simple-download-counter'), DOWNLOAD_COUNTER_VERSION);
			
		}
		
	}
	
	$GLOBALS['Simple_Download_Counter'] = $Simple_Download_Counter = new Simple_Download_Counter(); 
	
}