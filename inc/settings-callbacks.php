<?php // Simple Download Counter - Callbacks

if (!defined('ABSPATH')) exit;


function simple_download_counter_callback_settings() {
	
	echo '<p><strong>'. esc_html__('Thanks', 'simple-download-counter') .'</strong> '. esc_html__('for using Simple Download Counter (SDC). Need help?', 'simple-download-counter'); 
	
	echo ' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/simple-download-counter/">'. __('Visit the plugin docs &raquo;', 'simple-download-counter') .'</a></p>';
	
	echo '<p><strong>'. esc_html__('Note:', 'simple-download-counter') .'</strong> '. esc_html__('If you&rsquo;re getting 404 errors when trying to download, here is a', 'simple-download-counter');
	
	echo ' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/topic/note-about-404-errors-how-to-fix/">'. __('quick solution &raquo;', 'simple-download-counter') .'</a></p>';
	
}


//


function simple_download_counter_callback_text($args) {
	
	$id    = isset($args['id'])    ? $args['id']    : '';
	$label = isset($args['label']) ? $args['label'] : '';
	
	$options = simple_download_counter_options();
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$name = 'download_counter_options['. $id .']';
	
	echo '<input id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" type="text" size="40" class="regular-text" value="'. esc_attr($value) .'">';
	echo '<label for="'. esc_attr($name) .'">'. esc_html($label) .'</label>';
	
}


function simple_download_counter_callback_number($args) {
	
	$id      = isset($args['id'])      ? $args['id']      : '';
	$label   = isset($args['label'])   ? $args['label']   : '';
	
	$options = simple_download_counter_options();
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$name = 'download_counter_options['. $id .']';
	
	echo '<input id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" type="number" min="0" class="small-text" value="'. esc_attr($value) .'">';
	echo '<label for="'. esc_attr($name) .'" class="inline-block">'. esc_html($label) .'</label>';
	
}


function simple_download_counter_callback_image($args) {
	
	$id      = isset($args['id'])      ? $args['id']      : '';
	$label   = isset($args['label'])   ? $args['label']   : '';
	
	$options = simple_download_counter_options();
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$name = 'download_counter_options['. $id .']';
	
	echo '<input id="notif_icon" name="'. esc_attr($name) .'" type="text" size="40" class="regular-text" value="'. esc_attr($value) .'"> ';
	echo '<input id="notif_icon_button" type="button" class="button" value="'. esc_attr('Upload Image', 'simple-download-counter') .'">';
	echo '<label for="notif_icon">'. esc_html($label) .'</label>';
	
}


function simple_download_counter_callback_textarea($args) {
	
	$id      = isset($args['id'])      ? $args['id']      : '';
	$label   = isset($args['label'])   ? $args['label']   : '';
	
	$options = simple_download_counter_options();
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$name = 'download_counter_options['. $id .']';
	
	$allowed_tags = wp_kses_allowed_html('post');
	
	echo '<textarea id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" rows="3" cols="50" class="large-text code">'. wp_kses(stripslashes_deep($value), $allowed_tags) .'</textarea>';
	echo '<label for="'. esc_attr($name) .'">'. esc_html($label) .'</label>';
	
}


function simple_download_counter_callback_checkbox($args) {
	
	$id      = isset($args['id'])      ? $args['id']      : '';
	$label   = isset($args['label'])   ? $args['label']   : '';
	
	$options = simple_download_counter_options();
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$name = 'download_counter_options['. $id .']';
	
	echo '<input id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" type="checkbox" '. checked($value, 1, false) .' value="1"> ';
	echo '<label for="'. esc_attr($name) .'" class="inline-block">'. esc_html($label) .'</label>';
	
}


function simple_download_counter_callback_select($args) {
	
	$id      = isset($args['id'])      ? $args['id']      : '';
	$label   = isset($args['label'])   ? $args['label']   : '';
	
	$options = simple_download_counter_options();
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$name = 'download_counter_options['. $id .']';
	
	$options_array = array();
	
	echo '<select id="'. esc_attr($name) .'" name="'. esc_attr($name) .'">';
	
	foreach ($options_array as $option) {
		
		$option_value = isset($option['value']) ? $option['value'] : '';
		$option_label = isset($option['label']) ? $option['label'] : '';
		
		echo '<option '. selected($option_value, $value, false) .' value="'. esc_attr($option_value) .'">'. esc_html($option_label) .'</option>';
		
	}
	
	echo '</select> <label for="'. esc_attr($name) .'" class="inline-block">'. esc_html($label) .'</label>';
	
}


function simple_download_counter_callback_reset_options($args) {
	
	$nonce = wp_create_nonce('download-counter-reset-options');
	
	$href  = add_query_arg(array('download-counter-reset-options' => $nonce), admin_url('edit.php?post_type=sdc_download&page=download-counter-settings'));
	
	$label = isset($args['label']) ? $args['label'] : esc_html__('Restore default plugin options', 'simple-download-counter');
	
	echo '<a class="download-counter-reset-options" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}


function simple_download_counter_callback_rate($args) {
	
	$label = isset($args['label']) ? $args['label'] : '';
	
	$href  = 'https://wordpress.org/support/plugin/simple-download-counter/reviews/?rate=5#new-post';
	
	$title = __('Help keep Simple Download Counter going strong! A huge THANK YOU for your support!', 'simple-download-counter');
	
	echo '<a target="_blank" rel="noopener noreferrer" href="'. esc_url($href) .'" title="'. esc_attr($title) .'">'. esc_html($label) .'</a>';
	
}

function simple_download_counter_callback_support($args) {
	
	$href  = 'https://monzillamedia.com/donate.html';
	$title = esc_attr__('Donate via PayPal, credit card, or cryptocurrency', 'simple-download-counter');
	$text  = isset($args['label']) ? $args['label'] : esc_html__('Show support with a small donation&nbsp;&raquo;', 'simple-download-counter');
	
	echo '<a target="_blank" rel="noopener noreferrer" href="'. $href .'" title="'. $title .'">'. $text .'</a>';
	
}