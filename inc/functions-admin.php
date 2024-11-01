<?php // Simple Download Counter - Admin

if (!defined('ABSPATH')) exit;


function simple_download_counter_settings_flush_rewrite() {
	
	if (simple_download_counter_get_current_screen_pt() === 'sdc_download') {
		
		simple_download_counter_flush_rewrite_rules();
		
	}
	
}


function simple_download_counter_columns($columns) {
	
	unset($columns['date']);
	unset($columns['author']);
	
	$columns['permalink'] = __('Permalink', 'simple-download-counter');
	$columns['shortcode'] = __('Shortcode', 'simple-download-counter');
	$columns['location']  = __('Location',  'simple-download-counter');
	$columns['count']     = __('Count',     'simple-download-counter');
	
	$columns = array_slice($columns, 0, 2, true) + array('version' => __('Version', 'simple-download-counter')) + array_slice($columns, 2, null, true);
	
	return $columns;
	
}


function simple_download_counter_columns_sort($columns) {
	
	$columns['count'] = 'sdc_download_count';
	
	return $columns;
	
}


function simple_download_counter_columns_count($query) {
	
	global $pagenow, $typenow;
	
	if ($pagenow !== 'edit.php') return;
	
	if ($typenow !== 'sdc_download') return;
	
	$orderby = $query->get('orderby');
	
	if ($orderby === 'sdc_download_count') {
		
		$query->set('orderby', 'meta_value_num');
		
		$query->set('meta_query', array(
			
			'relation' => 'OR',
			
			array(
				'key'     => 'sdc_download_count',
				'compare' => 'NOT EXISTS',
			),
			
			array(
				'key'     => 'sdc_download_count',
				'compare' => 'EXISTS',
			),
				
		));
		
	}
	
}


function simple_download_counter_columns_data($column, $post_id) {
	
	$post_id = $post_id ? $post_id : 0;
	
	$count     = simple_download_counter_get_count($post_id);
	$permalink = simple_download_counter_download_url($post_id);
	$file_type = simple_download_counter_get_type($post_id);
	$shortcode = simple_download_counter_get_shortcode($post_id);
	$version   = simple_download_counter_get_version($post_id);
	$ext       = simple_download_counter_get_ext($post_id);
	
	switch ($column) {
	
		case 'permalink':
			
			echo '<button data-clipboard-target=".download-counter-permalink-'. esc_attr($post_id) .'" class="button button-primary dashicons dashicons-admin-links" title="'. esc_attr__('Copy Permalink', 'simple-download-counter') .'"></button>';
			
			echo '<div class="download-counter-permalink-'. esc_attr($post_id) .'">'. esc_url($permalink) .'</div>';
			
			break;
			
		case 'shortcode':
			
			echo '<button data-clipboard-target=".download-counter-shortcode-'. esc_attr($post_id) .'" class="button button-primary dashicons dashicons-shortcode" title="'. esc_attr__('Copy Shortcode', 'simple-download-counter') .'"></button>';
			
			echo '<div class="download-counter-shortcode-'. esc_attr($post_id) .'">'. esc_attr($shortcode) .'</div>';
			
			break;
			
		case 'location':
			
			echo '<span class="download-counter-type download-counter-type-'. esc_attr($file_type) .'">'. esc_html($file_type) .'</span> <span class="download-counter-ext download-counter-ext-'. esc_attr($ext) .'">'. esc_html($ext) .'</span>';
			
			break;
			
		case 'version':
			
			echo $version ? esc_html($version) : '<span>–</span>';
			
			break;
			
		case 'count':
			
			echo esc_html(number_format($count));
			
			break;
		
	}
	
}


function simple_download_counter_columns_category($post_type, $which) {
	
	if ($post_type !== 'sdc_download') return;
	
	$taxonomy = 'sdc_download_category';
	
	$selected = (isset($_REQUEST[$taxonomy])) ? sanitize_title($_REQUEST[$taxonomy]) : '';
	
	$args = array(
		
		'show_option_all'   => __('All Categories', 'simple-download-counter'),
		'show_option_none'  => false,
		'option_none_value' => false,
		'orderby'           => 'name',
		'pad_counts'        => false,
		'show_count'        => true,
		'echo'              => 1,
		'hierarchical'      => true,
		'depth'             => 3,
		'tab_index'         => 0,
		'name'              => $taxonomy,
		'id'                => $taxonomy,
		'class'             => 'postform',
		'selected'          => $selected,
		'value_field'       => 'slug',
		'taxonomy'          => $taxonomy,
		'hide_if_empty'     => false,
		'required'          => false,
		
	);
	
	wp_dropdown_categories($args);
	
}


function simple_download_counter_post_row_actions($actions) {
	
	if (get_post_type() === 'sdc_download') unset($actions['view']);
	
	return $actions;
	
}


//


function simple_download_counter_enter_title_here($text) {
	
	if (get_post_type() === 'sdc_download') {
		
		return esc_attr__('Download Title', 'simple-download-counter');
		
	}
	
	return $text;
	
}


function simple_download_counter_post_updated_messages($messages) {
	
	if (get_post_type() === 'sdc_download') $messages['post'][1] = __('Post updated.');
	
	return $messages;
	
}


function simple_download_counter_hide_preview_button() {
	
	if (simple_download_counter_get_current_screen_pt() === 'sdc_download') {
		
		echo '<style>#preview-action, .updated a { display: none; }</style>';
		
	}
	
}


function simple_download_counter_editor_height($settings) {
	
	if (simple_download_counter_get_current_screen_pt() === 'sdc_download') {
		
		$settings['height'] = '300';
		
	}
	
	return $settings;
	
}


function simple_download_counter_remove_meta_box($post_type, $post) {
	
	if ($post_type !== 'sdc_download') return;
	
	remove_meta_box('slugdiv', 'sdc_download', 'normal');
	
}


function simple_download_counter_hide_meta_box($hidden, $screen) {
	
	if (($screen->base === 'post') && ($screen->id === 'sdc_download')) {
		
		$hidden = array('authordiv', 'postimagediv', 'postcustom', 'tagsdiv-sdc_download_tag');
		
	}
	
	return $hidden;
	
}


function simple_download_counter_add_meta_box() {
	
	// add_meta_box($id, $title, $callback, $screen, $context, $priority, $callback_args)
	
	add_meta_box('simple-download-counter',         __('Download',         'simple-download-counter'), 'simple_download_counter_meta_box',         'sdc_download', 'side',   'high');
	add_meta_box('simple-download-counter-url',     __('Download URL',     'simple-download-counter'), 'simple_download_counter_meta_box_url',     'sdc_download', 'normal', 'core');
	add_meta_box('simple-download-counter-count',   __('Download Count',   'simple-download-counter'), 'simple_download_counter_meta_box_count',   'sdc_download', 'normal', 'core');
	add_meta_box('simple-download-counter-version', __('Download Version', 'simple-download-counter'), 'simple_download_counter_meta_box_version', 'sdc_download', 'normal', 'core');
	add_meta_box('simple-download-counter-notes',   __('Download Notes',   'simple-download-counter'), 'simple_download_counter_meta_box_notes',   'sdc_download', 'normal', 'core');
	
}


function simple_download_counter_meta_box() {
	
	$post_id = simple_download_counter_get_post_id();
	
	$count     = simple_download_counter_get_count($post_id);
	$permalink = simple_download_counter_download_url($post_id);
	$shortcode = simple_download_counter_get_shortcode($post_id);
	$version   = simple_download_counter_get_version($post_id);
	
	?>
	
	<ul class="download-counter-meta-box download-counter-meta-box-id">
		<li>
			<span class="dashicons dashicons-admin-links"></span> 
			<a href="<?php echo esc_url($permalink); ?>" title="<?php esc_attr_e('Direct link to download file', 'simple-download-counter'); ?>"><?php esc_html_e('Permalink', 'simple-download-counter'); ?></a>
		</li>
		<li><?php esc_html_e('Download ID:',      'simple-download-counter'); ?> <?php echo esc_html($post_id); ?></li>
		<li><?php esc_html_e('Download Count:',   'simple-download-counter'); ?> <?php echo esc_html(number_format($count)); ?></li>
		<li><?php esc_html_e('Download Version:', 'simple-download-counter'); ?> <?php echo esc_html($version); ?></li>
		<li>
			<code><?php echo $shortcode; ?></code> 
			<span>
				<?php esc_html_e('Visit the', 'simple-download-counter'); ?> 
				<a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/simple-download-counter/#installation"><?php esc_html_e('docs', 'simple-download-counter'); ?></a> 
				<?php esc_html_e('for shortcode options', 'simple-download-counter'); ?>
			</span>
		</li>
	</ul>
	
	<?php
	
}


function simple_download_counter_meta_box_url() {
	
	$post_id = simple_download_counter_get_post_id();
	
	$url  = simple_download_counter_get_url($post_id);
	$type = simple_download_counter_get_type($post_id);
	$size = simple_download_counter_get_size($post_id);
	$ext  = simple_download_counter_get_ext($post_id);
	
	$ext  = $ext ? ' <span class="download-counter-sep">&bull;</span> <span class="download-counter-ext">'. esc_html(strtoupper($ext)) .'</span>' : '';
	
	$size = ($size !== 'Unknown size') ? ' <span class="download-counter-sep">&bull;</span> <span class="download-counter-size">'. esc_html($size) .'</span>' : '';
	
	$type = $type ? '<div class="download-counter-meta download-counter-meta-'.  esc_attr($type) .'">'. esc_html($type) . __(' file', 'simple-download-counter') . $ext . $size .'</div>' : '';
	
	?>
	
	<div class="download-counter-meta-box download-counter-meta-box-url">
		
		<label for="sdc_download_url" class="download-counter-desc">
		
			<?php esc_html_e('Enter existing file', 'simple-download-counter'); ?> 
			<abbr title="<?php esc_attr_e('Uniform Resource Locator', 'simple-download-counter'); ?>"><?php esc_html_e('URL', 'simple-download-counter'); ?></abbr> 
			<?php esc_html_e('or click button to upload new file.', 'simple-download-counter'); ?>
			
		</label>
		
		<input id="sdc_download_url" name="sdc_download_url" type="text" class="regular-text" size="40" value="<?php echo esc_url($url); ?>" placeholder="<?php echo esc_attr_e('Download URL', 'simple-download-counter'); ?>">
		
		<span class="download-counter-line"></span>
		
		<input id="sdc_download_upload" type="button" class="button" value="<?php esc_attr_e('Upload File', 'simple-download-counter'); ?>">
		
		<?php echo $type; ?>
		
		<?php wp_nonce_field('simple-download-counter-url', 'simple-download-counter-url'); ?>
		
	</div>
	
	<?php
	
}


function simple_download_counter_meta_box_count() {
	
	$post_id = simple_download_counter_get_post_id();
	
	$count = simple_download_counter_get_count($post_id);
	
	?>
	
	<div class="download-counter-meta-box download-counter-meta-box-count">
		
		<label for="sdc_download_count" class="download-counter-desc"><?php esc_html_e('Enter download count.', 'simple-download-counter'); ?></label>
		
		<input id="sdc_download_count" name="sdc_download_count" type="number" min="0" step="1" class="small-text" size="40" value="<?php echo esc_attr($count); ?>" placeholder="0">
		
		<?php wp_nonce_field('simple-download-counter-count', 'simple-download-counter-count'); ?>
		
	</div>
	
	<?php
	
}


function simple_download_counter_meta_box_version() {
	
	$post_id = simple_download_counter_get_post_id();
	
	$version = simple_download_counter_get_version($post_id);
	
	?>
	
	<div class="download-counter-meta-box download-counter-meta-box-version">
		
		<label for="sdc_download_version" class="download-counter-desc"><?php esc_html_e('Enter download version.', 'simple-download-counter'); ?></label>
		
		<input id="sdc_download_version" name="sdc_download_version" type="text" class="regular-text" size="40" value="<?php echo esc_attr($version); ?>" placeholder="<?php echo esc_attr_e('Download version', 'simple-download-counter'); ?>">
		
		<?php wp_nonce_field('simple-download-counter-version', 'simple-download-counter-version'); ?>
		
	</div>
	
	<?php
	
}


function simple_download_counter_meta_box_notes() {
	
	$post_id = simple_download_counter_get_post_id();
	
	$notes = simple_download_counter_get_notes($post_id);
	
	?>
	
	<div class="download-counter-meta-box download-counter-meta-box-notes">
		
		<label for="sdc_download_notes" class="download-counter-desc"><?php esc_html_e('Enter any notes about this download (all kept private).', 'simple-download-counter'); ?></label>
		
		<textarea id="sdc_download_notes" name="sdc_download_notes" rows="5" cols="50" placeholder="<?php echo esc_attr_e('Download notes..', 'simple-download-counter'); ?>"><?php echo esc_textarea($notes); ?></textarea>
		
		<?php wp_nonce_field('simple-download-counter-notes', 'simple-download-counter-notes'); ?>
		
	</div>
	
	<?php
	
}


//


function simple_download_counter_meta_box_process($post_id) {
	
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	
	if (!current_user_can('edit_post', $post_id)) return;
	
	// version
	
	if (!isset($_POST['simple-download-counter-version']) || empty($_POST['simple-download-counter-version'])) return;
	
	if (!wp_verify_nonce($_POST['simple-download-counter-version'], 'simple-download-counter-version')) return;
	
	if (!isset($_POST['sdc_download_version'])) return;
	
	$version = sanitize_textarea_field($_POST['sdc_download_version']);
	
	update_post_meta($post_id, 'sdc_download_version', $version);
	
	// count
	
	if (!isset($_POST['simple-download-counter-count']) || empty($_POST['simple-download-counter-count'])) return;
	
	if (!wp_verify_nonce($_POST['simple-download-counter-count'], 'simple-download-counter-count')) return;
	
	if (!isset($_POST['sdc_download_count'])) return;
	
	$count = sanitize_textarea_field($_POST['sdc_download_count']);
	
	$count = empty($count) ? 0 : preg_replace('/[^0-9]/', '', $count);
	
	update_post_meta($post_id, 'sdc_download_count', $count);
	
	// notes
	
	if (!isset($_POST['simple-download-counter-notes']) || empty($_POST['simple-download-counter-notes'])) return;
	
	if (!wp_verify_nonce($_POST['simple-download-counter-notes'], 'simple-download-counter-notes')) return;
	
	if (!isset($_POST['sdc_download_notes'])) return;
	
	$notes = sanitize_textarea_field($_POST['sdc_download_notes']);
	
	update_post_meta($post_id, 'sdc_download_notes',   $notes);
	
	// url
	
	if (!isset($_POST['simple-download-counter-url']) || empty($_POST['simple-download-counter-url'])) return;
	
	if (!wp_verify_nonce($_POST['simple-download-counter-url'], 'simple-download-counter-url')) return;
	
	if (!isset($_POST['sdc_download_url']) || empty($_POST['sdc_download_url'])) return;
	
	$url = $_POST['sdc_download_url'];
	
	$url_decode = urldecode($url);
	
	$url = ($url === $url_decode) ? $url : $url_decode;
	
	if (wp_http_validate_url($url)) {
		
		list($file_url, $file_path, $remote) = simple_download_counter_parse_path($url);
		
	} else {
		
		wp_die(__('Error: Invalid Download URL', 'simple-download-counter'), __('Download Error', 'simple-download-counter'));
		
	}
	
	if (is_wp_error($file_url)) {
		
		wp_die($file_url->get_error_message(), __('Download Error', 'simple-download-counter'));
		
	} else {
		
		$file_type = $remote ? __('remote', 'simple-download-counter') : __('local', 'simple-download-counter');
		
		$size = $remote ? 0 : filesize($file_path);
		
		$size = $size ? $size : 0;
		
		$ext  = pathinfo($file_path, PATHINFO_EXTENSION);
		
		$ext  = ($file_type === 'local') ? $ext : '';
		
		update_post_meta($post_id, 'sdc_download_url',  $file_url);
		update_post_meta($post_id, 'sdc_download_path', $file_path);
		update_post_meta($post_id, 'sdc_download_type', $file_type);
		update_post_meta($post_id, 'sdc_download_size', $size);
		update_post_meta($post_id, 'sdc_download_ext',  $ext);
		
		$hash = get_post_meta($post_id, 'sdc_download_hash', true);
		
		if (!$hash) {
			
			$hash = simple_download_counter_random_hash(30);
			
			update_post_meta($post_id, 'sdc_download_hash', $hash);
			
		}
		
	}
		
}


//


function simple_download_counter_parse_path($file_path) {
	
	$remote = false;
	
	$file_url = $file_path;
	
	$parsed = parse_url($file_path);
	$scheme = isset($parsed['scheme']) ? $parsed['scheme'] : null;
	$path   = isset($parsed['path'])   ? $parsed['path']   : null;
	$host   = isset($parsed['host'])   ? $parsed['host']   : null;
	
	$domain   = $scheme .'://'. $host;
	$domain   = filter_var($domain, FILTER_VALIDATE_URL) ? sanitize_url($domain) : null;
	$doc_root = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : null;
	
	//
	
	$wp_uploads     = wp_upload_dir();
	$wp_uploads_dir = isset($wp_uploads['basedir']) ? $wp_uploads['basedir'] : null;
	$wp_uploads_url = isset($wp_uploads['baseurl']) ? $wp_uploads['baseurl'] : null;
	
	$wp_uploads_host = parse_url($wp_uploads_url);
	$wp_uploads_host = isset($wp_uploads_host['host']) ? $wp_uploads_host['host'] : null;
	
	//
	
	if ((empty($scheme) || !in_array($scheme, array('http', 'https', 'ftp'))) && !empty($path) && file_exists($path)) {
		
		// absolute path
		
		$file_path = simple_download_counter_check_path($file_path, 1);
		$file_url  = is_wp_error($file_path) ? $file_path : simple_download_counter_convert_path($file_path, $doc_root, $wp_uploads_host, 2);
		
	} elseif (strpos($file_path, $wp_uploads_url) !== false) {
		
		// uploads dir url
		
		$file_path = str_replace($wp_uploads_url, $wp_uploads_dir, $file_path);
		$file_path = simple_download_counter_check_path($file_path, 3);
		$file_url  = is_wp_error($file_path) ? $file_path : $file_url;
		
	} elseif (is_multisite() && ((strpos($file_path, network_site_url('/', 'http')) !== false) || (strpos($file_path, network_site_url('/', 'https')) !== false))) {
		
		// multisite url
		
		$file_path = str_replace(network_site_url('/', 'https'), ABSPATH, $file_path);
		$file_path = str_replace(network_site_url('/', 'http'),  ABSPATH, $file_path);
		$file_path = str_replace($wp_uploads_url, $wp_uploads_dir, $file_path);
		$file_path = simple_download_counter_check_path($file_path, 4);
		$file_url  = is_wp_error($file_path) ? $file_path : $file_url;
		
	} elseif (strpos($file_path, site_url('/', 'http')) !== false || strpos($file_path, site_url('/', 'https')) !== false) {
		
		// wp url
		
		$file_path = str_replace(site_url('/', 'https'), ABSPATH, $file_path);
		$file_path = str_replace(site_url('/', 'http'),  ABSPATH, $file_path);
		$file_path = simple_download_counter_check_path($file_path, 5);
		$file_url  = is_wp_error($file_path) ? $file_path : $file_url;
		
	} elseif ($domain && strpos($file_path, $domain) !== false) { 
		
		// domain url
		
		if ($wp_uploads_host === $host) {
			
			// local file
			
			$file_path = str_replace($domain, $doc_root, $file_path);
			$file_path = simple_download_counter_check_path($file_path, 6);
			$file_url  = is_wp_error($file_path) ? $file_path : $file_url;
			
		} else {
			
			// remote file
			
			$file_path = simple_download_counter_check_remote($file_path, 7);
			$file_url  = is_wp_error($file_path) ? $file_path : $file_url;
			
			$remote = true;
			
		}
		
	} elseif (file_exists(ABSPATH . $file_path)) {
		
		// partial path
		
		$file_path = ABSPATH . $file_path;
		$file_path = simple_download_counter_check_path($file_path, 8);
		$file_url  = is_wp_error($file_path) ? $file_path : simple_download_counter_convert_path($file_path, $doc_root, $wp_uploads_host, 9);
		
	} else {
		
		// doesn't exist
		
		$file_path = new WP_Error('file_does_not_exist', __('File does not exist. Error code: 10', 'simple-download-counter'));
		$file_url  = is_wp_error($file_path) ? $file_path : $file_url;
		
	}
	
	//
	
	do_action('simple_download_counter_parse_path', array($file_url, $file_path, $remote));
	
	return array($file_url, $file_path, $remote);
	
}


function simple_download_counter_convert_path($file_path, $doc_root, $wp_uploads_host, $error_id) {
	
	$protocol  = is_ssl() ? 'https://' : 'http://';
	
	$file_path = str_ireplace($doc_root, '', $file_path);
	
	$file_path = $protocol . $wp_uploads_host . $file_path;
	
	$file_path = filter_var($file_path, FILTER_VALIDATE_URL) ? $file_path : null;
	
	$file_path = empty($file_path) ? new WP_Error('file_does_not_exist', __('File does not exist. Error code: ', 'simple-download-counter') . $error_id) : $file_path;
	
	return $file_path;
	
}


function simple_download_counter_check_path($file_path, $error_id) {
	
	$file_path = realpath($file_path);
	
	$file_path = wp_normalize_path($file_path);
	
	$file_path = (empty($file_path) || !is_file($file_path)) ? new WP_Error('file_does_not_exist', __('File does not exist. Error code: ', 'simple-download-counter') . $error_id) : $file_path;
	
	return $file_path;
	
}


function simple_download_counter_check_remote($file_path, $error_id) {
	
	$headers   = @get_headers($file_path, true);
	$response  = ($headers && isset($headers[0])) ? $headers[0] : null;
	$file_path = (strpos($response, '200') === false) ? new WP_Error('file_does_not_exist', __('File does not exist. Error code: ', 'simple-download-counter') . $error_id) : $file_path;
	
	return $file_path;
	
}


function simple_download_counter_random_hash($length = 10) {
	
	$key = '';
	
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	
	$characters_length = strlen($characters);
	
	for ($i = 0; $i < $length; $i++) {
		
		$key .= $characters[rand(0, $characters_length - 1)];
		
	}
	
	return $key;
	
}
