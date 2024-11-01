<?php // Simple Download Counter - Shortcodes

if (!defined('ABSPATH')) exit;


function simple_download_counter_meta($atts) {
	
	extract(shortcode_atts(array(
		
		'id'     => null, // required
		
		'title'   => 'false',
		'url'     => 'false',
		'count'   => 'false',
		'version' => 'false',
		'type'    => 'false',
		'ext'     => 'false',
		'size'    => 'false',
		
	), $atts, 'sdc_meta'));
	
	if (empty($id) || !is_numeric($id)) {
		
		return __('Invalid Download ID', 'simple-download-counter');
		
	}
	
	//
	
	list($download_title, $download_url, $download_count, $download_version, $download_type, $download_ext, $download_size) = simple_download_counter_props($id);
	
	//
	
	$output = '';
	
	$output .= ($title   === 'true') ? '<span class="sdc-meta-title">'  . esc_html($download_title)   .'</span> ' : '';
	$output .= ($url     === 'true') ? '<span class="sdc-meta-url">'    . esc_html($download_url)     .'</span> ' : '';
	$output .= ($count   === 'true') ? '<span class="sdc-meta-count">'  . esc_html($download_count)   .'</span> ' : '';
	$output .= ($version === 'true') ? '<span class="sdc-meta-version">'. esc_html($download_version) .'</span> ' : '';
	$output .= ($type    === 'true') ? '<span class="sdc-meta-type">'   . esc_html($download_type)    .'</span> ' : '';
	$output .= ($ext     === 'true') ? '<span class="sdc-meta-ext">'    . esc_html($download_ext)     .'</span> ' : '';
	$output .= ($size    === 'true') ? '<span class="sdc-meta-size">'   . esc_html($download_size)    .'</span> ' : '';
	
	return $output;
	
}


function simple_download_counter_downloads_published($atts) {
	
	$count = (int) wp_count_posts('sdc_download')->publish;
	
	return is_int($count) ? number_format($count) : 0;
	
}


function simple_download_counter_count_total($atts = array()) {
	
	$posts = get_posts(array('post_type' => 'sdc_download', 'meta_key' => 'sdc_download_count', 'post_status' => 'publish', 'posts_per_page' => -1));
	
	$counts = [];
	
	if ($posts) {
		
		foreach ($posts as $post) {
			
			$count = get_post_meta($post->ID, 'sdc_download_count', true);
			
			$counts[] = is_numeric($count) ? $count : 0;
			
		}
		
	}
	
	$total = array_sum($counts);
	
	$total = number_format($total, 0, '.', apply_filters('simple_download_counter_total_sep', ','));
	
	return $total;
	
}


//


function simple_download_counter_download($atts) {
	
	extract(shortcode_atts(array(
		
		'id'     => null, // required
		
		'wrap'   => 'p', // p, div, span, none
		'text'   => '%title%',
		'title'  => '%count% '. __('downloads', 'simple-download-counter'),
		'class'  => '',
		'type'   => '',
		
		'before' => '',
		'after'  => '',
		
	), $atts, 'sdc_download'));
	
	if (empty($id) || !is_numeric($id)) {
		
		return __('Invalid Download ID', 'simple-download-counter');
		
	}
	
	//
	
	$array = array('text' => $text, 'title' => $title, 'before' => htmlspecialchars($before, ENT_QUOTES), 'after' => htmlspecialchars($after, ENT_QUOTES));
	
	list($array, $text, $title, $before, $after, $download_url) = simple_download_counter_download_vars($id, $array);
	
	//
	
	if ($class) {
		
		$classes = '';
		
		$class = array_map('trim', explode(',', $class));
		
		foreach ($class as $key => $value) $classes .= ' '. sanitize_html_class($value);
		
		$class = $classes;
		
	}
	
	//
	
	$output = '';
	
	if ($wrap === 'p') {
		
		$output .= '<p id="simple-download-counter-'. esc_attr($id) .'" class="simple-download-counter'. $class .'">';
		
	} elseif ($wrap === 'div') {
		
		$output .= '<div id="simple-download-counter-'. esc_attr($id) .'" class="simple-download-counter'. $class .'">';
		
	} elseif ($wrap === 'span') {
		
		$output .= '<span id="simple-download-counter-'. esc_attr($id) .'" class="simple-download-counter'. $class .'">';
		
	} // if empty = no wrap markup
	
	//
	
	if ($type === 'button') {
		
		$download_key = parse_url($download_url, PHP_URL_QUERY);
		
		parse_str($download_key, $download_key);
		
		$download_key = isset($download_key['key']) ? $download_key['key'] : null;
		
		if ($download_key) {
			
			$style = apply_filters('simple_download_counter_button_style', ' style="display:inline-block;"');
			
			$output .= $before .'<form method="get" action="'. esc_url($download_url) .'" class="simple-download-counter-button"'. $style .'>';
			
			$output .= '<input type="hidden" name="key" value="'. esc_attr($download_key) .'">';
			
			$output .= '<button type="submit" title="'. esc_attr($title) .'">'. esc_html($text) .'</button></form>'. $after;
			
		} else {
			
			$output .= __('Invalid Download Key', 'simple-download-counter');
			
		}
		
	} elseif ($type === 'none') {
		
		$output .= $before . $after;
		
	} else {
		
		$output .= $before .'<a class="simple-download-counter-link" href="'. esc_url($download_url) .'" title="'. esc_attr($title) .'">'. esc_html($text) .'</a>'. $after;
		
	}
	
	//
	
	if ($wrap === 'p') {
		
		$output .= '</p>';
		
	} elseif ($wrap === 'div') {
		
		$output .= '</div>';
		
	} elseif ($wrap === 'span') {
		
		$output .= '</span>';
		
	} // if empty = no wrap markup
	
	return $output;
	
}


function simple_download_counter_props($id) {
	
	$download = get_post(absint($id));
	
	if (empty($download)) return false;
	
	$download_title = $download->post_title;
	
	$download_url     = simple_download_counter_download_url($id);
	$download_count   = simple_download_counter_get_count($id);
	$download_version = simple_download_counter_get_version($id);
	$download_type    = simple_download_counter_get_type($id);
	$download_ext     = simple_download_counter_get_ext($id);
	$download_size    = simple_download_counter_get_size($id);
	
	return array($download_title, $download_url, $download_count, $download_version, $download_type, $download_ext, $download_size);
		
}


function simple_download_counter_download_vars($id, $array) {
	
	list($download_title, $download_url, $download_count, $download_version, $download_type, $download_ext, $download_size) = simple_download_counter_props($id);
	
	foreach ($array as $key => $value) {
		
		if ($key === 'text' || $key === 'title') {
			
			$array[$key] = str_replace('%id%',      $id,                            $array[$key]);
			$array[$key] = str_replace('%title%',   $download_title,                $array[$key]);
			$array[$key] = str_replace('%version%', $download_version,              $array[$key]);
			$array[$key] = str_replace('%type%',    ucfirst($download_type),        $array[$key]);
			$array[$key] = str_replace('%ext%',     strtoupper($download_ext),      $array[$key]);
			$array[$key] = str_replace('%count%',   number_format($download_count), $array[$key]);
			$array[$key] = str_replace('%size%',    $download_size,                 $array[$key]);
			
		} else {
			
			$array[$key] = str_replace('%id%',      '<span class="simple-download-counter-id">'.      $id                            .'</span>', $array[$key]);
			$array[$key] = str_replace('%title%',   '<span class="simple-download-counter-title">'.   $download_title                .'</span>', $array[$key]);
			$array[$key] = str_replace('%version%', '<span class="simple-download-counter-version">'. $download_version              .'</span>', $array[$key]);
			$array[$key] = str_replace('%type%',    '<span class="simple-download-counter-type">'.    ucfirst($download_type)        .'</span>', $array[$key]);
			$array[$key] = str_replace('%ext%',     '<span class="simple-download-counter-ext">'.     strtoupper($download_ext)      .'</span>', $array[$key]);
			$array[$key] = str_replace('%count%',   '<span class="simple-download-counter-count">'.   number_format($download_count) .'</span>', $array[$key]);
			$array[$key] = str_replace('%size%',    '<span class="simple-download-counter-size">'.    $download_size                 .'</span>', $array[$key]);
			
		}
		
	}
	
	$text   = isset($array['text'])   ? $array['text']  : '';
	
	$title  = isset($array['title'])  ? $array['title'] : '';
	
	$before = (isset($array['before']) && !empty($array['before'])) ? '<span class="simple-download-counter-before">'. $array['before'] .'</span>' : '';
	
	$after  = (isset($array['after'])  && !empty($array['after']))  ? '<span class="simple-download-counter-after">'.  $array['after']  .'</span>' : '';
	
	return array($array, $text, $title, $before, $after, $download_url);
	
}


function simple_download_counter_download_url($id) {
	
	$scheme = parse_url(get_option('home'), PHP_URL_SCHEME);
	
	$key = simple_download_counter_key();
	
	if (get_option('permalink_structure')) {
		
		$url = home_url('/' . $key . '/' . $id . '/', $scheme);
		
	} else {
		
		$url = add_query_arg($key, $id, home_url('', $scheme));
	
	}
	
	$hash = get_post_meta($id, 'sdc_download_hash', true);
	
	if ($hash) {
		
		$url = add_query_arg('key', $hash, $url);
		
	}
	
	return apply_filters('simple_download_counter_get_url', esc_url_raw($url));
	
}


//


function simple_download_counter_count($atts) {
	
	extract(shortcode_atts(array(
		
		'id'     => null, // required
		
		'wrap'   => 'p', // p, div, span, none
		'class'  => '',
		
		'before' => '%title% - ',
		'after'  => ' '. esc_html__('Downloads', 'simple-download-counter'),
		
	), $atts, 'sdc_count'));
	
	if (empty($id) || !is_numeric($id)) {
		
		return __('Invalid Download ID', 'simple-download-counter');
		
	}
	
	//
	
	$array = array('before' => $before, 'after' => $after);
	
	list($array, $before, $after, $download_count) = simple_download_counter_count_vars($id, $array);
	
	//
	
	if ($class) {
		
		$classes = '';
		
		$class = array_map('trim', explode(',', $class));
		
		foreach ($class as $key => $value) $classes .= ' '. sanitize_html_class($value);
		
		$class = $classes;
		
	}
	
	//
	
	$output = '';
	
	if ($wrap === 'p') {
		
		$output .= '<p id="sdc-download-count-'. esc_attr($id) .'" class="sdc-download-count'. $class .'">';
		
	} elseif ($wrap === 'div') {
		
		$output .= '<div id="sdc-download-count-'. esc_attr($id) .'" class="sdc-download-count'. $class .'">';
		
	} elseif ($wrap === 'span') {
		
		$output .= '<span id="sdc-download-count-'. esc_attr($id) .'" class="sdc-download-count'. $class .'">';
		
	}
	
	$output .= $before .'<span class="sdc-count">'. number_format($download_count) .'</span>'. $after;
	
	if ($wrap === 'p') {
		
		$output .= '</p>';
		
	} elseif ($wrap === 'div') {
		
		$output .= '</div>';
		
	} elseif ($wrap === 'span') {
		
		$output .= '</span>';
		
	}
	
	return $output;
	
}


function simple_download_counter_count_vars($id, $array) {
	
	list($download_title, $download_url, $download_count, $download_version, $download_type, $download_ext, $download_size) = simple_download_counter_props($id);
	
	foreach ($array as $key => $value) {
		
		$array[$key] = str_replace('%id%',      '<span class="sdc-id">'      . $id                       .'</span>', $array[$key]);
		$array[$key] = str_replace('%title%',   '<span class="sdc-title">'   . $download_title           .'</span>', $array[$key]);
		$array[$key] = str_replace('%version%', '<span class="sdc-version">' . $download_version         .'</span>', $array[$key]);
		$array[$key] = str_replace('%type%',    '<span class="sdc-type">'    . ucfirst($download_type)   .'</span>', $array[$key]);
		$array[$key] = str_replace('%ext%',     '<span class="sdc-ext">'     . strtoupper($download_ext) .'</span>', $array[$key]);
		$array[$key] = str_replace('%size%',    '<span class="sdc-size">'    . $download_size            .'</span>', $array[$key]);
		
	}
	
	$before = (isset($array['before']) && !empty($array['before'])) ? '<span class="sdc-before">'. $array['before'] .'</span>' : '';
	
	$after  = (isset($array['after'])  && !empty($array['after']))  ? '<span class="sdc-after">'.  $array['after']  .'</span>' : '';
	
	return array($array, $before, $after, $download_count);
	
}


//


function simple_download_counter_add_mce_plugin($plugins) {
	
	$plugins['sdc_download'] = DOWNLOAD_COUNTER_URL .'js/tinymce.js';
	
	return $plugins;
	
}


function simple_download_counter_add_mce_button($buttons) {
	
	array_push($buttons, 'sdc_download');
	
	return $buttons;
	
}


function simple_download_counter_add_mce() {
	
	global $pagenow;
	
	if ($pagenow === 'post.php' || $pagenow === 'post-new.php') {
		
		add_filter('mce_external_plugins', 'simple_download_counter_add_mce_plugin', 10);
		add_filter('mce_buttons',          'simple_download_counter_add_mce_button', 10);
		
	}
	
}


function simple_download_counter_add_quicktags() {
	
	global $pagenow;
	
	if (($pagenow === 'post.php' || $pagenow === 'post-new.php') && wp_script_is('quicktags')) : 
	
	// QTags.addButton(id, display, arg1, arg2, access_key, title, priority, instance);
	
	?>
	
	<script type="text/javascript">
		window.onload = function() {
			QTags.addButton('sdc_download', '&darr;', '[sdc_download id=""]', null, 'z', 'Add Download');
		};
	</script>
	
<?php endif;

}