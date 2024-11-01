<?php // Simple Download Counter - Core

if (!defined('ABSPATH')) exit;


function simple_download_counter_options() {
	
	global $Simple_Download_Counter;
	
	if (!$Simple_Download_Counter) return;
	
	$default = call_user_func(array($Simple_Download_Counter, 'options'));
	
	return get_option('download_counter_options', $default);
	
}


function simple_download_counter_key() {
	
	$options = simple_download_counter_options();
	
	return isset($options['download_key']) ? $options['download_key'] : null;
	
}


function simple_download_counter_get_post_id() {
	
	global $post;
	
	return isset($post->ID) ? $post->ID : 0;
	
}


function simple_download_counter_get_shortcode($id) {
	
	return '[sdc_download id="'. $id .'"]';
	
}


function simple_download_counter_get_url($id) {
	
	return get_post_meta($id, 'sdc_download_url', true);
	
}


function simple_download_counter_get_version($id) {
	
	return get_post_meta($id, 'sdc_download_version', true);
	
}


function simple_download_counter_get_count($id) {
	
	$count = get_post_meta($id, 'sdc_download_count', true);
	
	return $count ? $count : 0;
	
}


function simple_download_counter_get_type($id) {
	
	$type = get_post_meta($id, 'sdc_download_type', true);
	
	return $type ? $type : 'undefined';
	
}


function simple_download_counter_get_notes($id) {
	
	return get_post_meta($id, 'sdc_download_notes', true);
	
}


function simple_download_counter_get_size($id) {
	
	$size = get_post_meta($id, 'sdc_download_size', true);
	
	return simple_download_counter_format_size($size);
	
}


function simple_download_counter_get_ext($id) {
	
	$ext = get_post_meta($id, 'sdc_download_ext', true);
	
	return $ext ? $ext : '';
	
}


function simple_download_counter_format_size($bytes) {
	
	if ($bytes >= 1073741824) {
		
		$bytes = number_format($bytes / 1073741824, 2) .' GB';
		
	} elseif ($bytes >= 1048576) {
		
		$bytes = number_format($bytes / 1048576, 2) .' MB';
		
	} elseif ($bytes >= 1024) {
		
		$bytes = number_format($bytes / 1024, 2) .' KB';
		
	} elseif ($bytes > 1) {
		
		$bytes = $bytes .' bytes';
		
	} elseif ($bytes == 1) {
		
		$bytes = $bytes .' byte';
		
	} else {
		
		$bytes = 'Unknown size';
		
	}
	
	return $bytes;
	
}


function simple_download_counter_current_page() {
	
	$url = home_url();
	
	$text = __('Go to homepage &raquo;', 'simple-download-counter');
	
	$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
	
	if (!empty($referrer)) {
		
		if (filter_var($referrer, FILTER_VALIDATE_URL)) {
			
			$url = $referrer;
			
			$text = __('Return to previous page &#10550;', 'simple-download-counter');
			
		}
		
	}
	
	return array($url, $text);
	
}


function simple_download_counter_get_current_screen_id() {
	
	if (!function_exists('get_current_screen')) require_once ABSPATH .'/wp-admin/includes/screen.php';
	
	$screen = get_current_screen();
	
	if ($screen && property_exists($screen, 'id')) return $screen->id;
	
	return false;
	
}


function simple_download_counter_get_current_screen_pt() {
	
	if (!function_exists('get_current_screen')) require_once ABSPATH .'/wp-admin/includes/screen.php';
	
	$screen = get_current_screen();
	
	if ($screen && property_exists($screen, 'post_type')) return $screen->post_type;
	
	return false;
	
}


function simple_download_counter_add_count($id) {
	
	$count = get_post_meta($id, 'sdc_download_count', true);
	
	update_post_meta($id, 'sdc_download_count', absint($count) + 1);
	
}


function simple_download_counter_add_rewrite() {
	
	add_rewrite_endpoint(simple_download_counter_key(), EP_ALL);
	
}


//


function simple_download_counter_flush_rewrite_rules() {
	
	flush_rewrite_rules();
	
}


function simple_download_counter_updated_option($new, $old, $option) {
	
	$key_new = isset($new['download_key']) ? $new['download_key'] : null;
	
	$key_old = isset($old['download_key']) ? $old['download_key'] : null;
	
	if ($key_new !== $key_old) set_transient('update_sdc_download_permalinks', true);
	
	return $new;
	
}


function simple_download_counter_updated_option_flush() {
	
	if (get_transient('update_sdc_download_permalinks')) {
			
		flush_rewrite_rules();
		
		delete_transient('update_sdc_download_permalinks');
		
	}
	
}


//


function simple_download_counter_download_handler($query) {
	
	global $wp;
	
	list($url, $text) = simple_download_counter_current_page();
	
	$current = ' <a href="'. esc_url($url) .'">'. esc_html($text) .'</a>';
	
	$title = __('Download Error', 'simple-download-counter');
	
	//
	
	$key = simple_download_counter_key();
	
	if (empty($key)) {
		
		wp_die(__('Download key is undefined.', 'simple-download-counter') . $current, $title);
		
	}
	
	if (isset($_GET[$key]) && !empty($_GET[$key])) {
		
		$wp->query_vars[$key] = sanitize_key($_GET[$key]);
		
	}
	
	if (isset($wp->query_vars[$key]) && !empty($wp->query_vars[$key])) {
		
		if (!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE', true);
		
		$id = sanitize_title(wp_unslash($wp->query_vars[$key]));
		
		if (empty($id)) {
			
			wp_die(__('Download ID is undefined.', 'simple-download-counter') . $current, $title);
			
		}
		
		$hash = get_post_meta($id, 'sdc_download_hash', true);
		
		if ($hash) {
			
			$hash_key = (isset($_GET['key']) && !empty($_GET['key'])) ? $_GET['key'] : '';
			
			$hash_key = apply_filters('simple_download_counter_download_hash', $hash_key, $id, $hash);
			
			if (!$hash_key || $hash_key !== $hash) {
				
				wp_die(__('Download hash does not match.', 'simple-download-counter') . $current, $title);
				
			}
			
		}
		
		if (get_post_status($id) !== 'publish') {
			
			wp_die(__('Download is not available.', 'simple-download-counter') . $current, $title);
			
		}
		
		$file_url = get_post_meta($id, 'sdc_download_url', true);
		
		if (empty($file_url)) {
			
			wp_die(__('File URL is not defined.', 'simple-download-counter') . $current, $title);
			
		}
		
		$file_path = get_post_meta($id, 'sdc_download_path', true);
		
		if (empty($file_path)) {
			
			wp_die(__('File path is not defined.', 'simple-download-counter') . $current, $title);
			
		}
		
		$file_type = get_post_meta($id, 'sdc_download_type', true);
		
		if (empty($file_type)) {
			
			wp_die(__('File type is not defined.', 'simple-download-counter') . $current, $title);
			
		}
		
		do_action('simple_download_counter_before_download', $id, $file_url);
		
		//
		
		if ($file_type === 'local') {
			
			// DOWNLOAD HEADERS FOR LOCAL FILES
			
			if (function_exists('simple_download_counter_download_handler_local')) {
				
				simple_download_counter_download_handler_local($id, $file_path);
				
			} else {
				
				simple_download_counter_add_count($id);
				
				$file_name = wp_basename(parse_url($file_path, PHP_URL_PATH));
				
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'. $file_name .'"');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length:'. filesize($file_path));
				header('Connection: Keep-Alive');
				header('Expires: 0');
				header('Cache-Control: no-cache, no-store, must-revalidate');
				header('Pragma: no-cache');
				
				while (ob_get_level()) ob_end_clean();
				
				readfile($file_path);
				
				exit;
				
			}
			
		} elseif ($file_type === 'remote') {
			
			// DOWNLOAD HEADERS FOR REMOTE FILES
			
			if (function_exists('simple_download_counter_download_handler_remote')) {
				
				simple_download_counter_download_handler_remote($id, $file_path, $current, $title);
				
			} else {
				
				// allow_url_fopen required
				
				if (!ini_get('allow_url_fopen')) {
					
					wp_die(__('Error: allow_url_fopen not enabled.', 'simple-download-counter') . $current, $title);
					
				}
				
				simple_download_counter_add_count($id);
				
				$file_name = wp_basename(parse_url($file_path, PHP_URL_PATH));
				
				$file_ext = pathinfo(wp_basename($file_path), PATHINFO_EXTENSION);
				
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'. $file_name .'"');
				header('Content-Transfer-Encoding: binary');
				header('Connection: Keep-Alive');
				header('Expires: 0');
				header('Cache-Control: no-cache, no-store, must-revalidate');
				header('Pragma: no-cache');
				
				if (empty($file_ext)) {
					
					if (function_exists('get_headers')) {
						
						$file_headers = get_headers($file_path, true);
						
						if (isset($file_headers['Content-Disposition']) && !empty($file_headers['Content-Disposition'])) {
							
							header('Content-Disposition: '. $file_headers['Content-Disposition']);
							
						}
						
					}
					
				}
				
				while (ob_get_level()) ob_end_clean();
				
				readfile($file_path);
				
				exit;
				
			}
			
		}
		
	}
	
}