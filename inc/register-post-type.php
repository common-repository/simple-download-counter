<?php // Simple Download Counter - Custom Post Type

if (!defined('ABSPATH')) exit;


function simple_download_counter_post_type() {
	
	$labels = array(
		
		'name'                  => __('Downloads',                    'simple-download-counter'),
		'singular_name'         => __('Download',                     'simple-download-counter'),
		'menu_name'             => __('Downloads',                    'simple-download-counter'),
		'name_admin_bar'        => __('Download',                     'simple-download-counter'),
		'add_new'               => __('Add New',                      'simple-download-counter'),
		'add_new_item'          => __('Add New Download',             'simple-download-counter'),
		'new_item'              => __('New Download',                 'simple-download-counter'),
		'edit'                  => __('Edit',                         'simple-download-counter'),
		'edit_item'             => __('Edit Download',                'simple-download-counter'),
		'view'                  => __('View Download',                'simple-download-counter'),
		'view_item'             => __('View Download',                'simple-download-counter'),
		'all_items'             => __('All Downloads',                'simple-download-counter'),
		'search_items'          => __('Search Downloads',             'simple-download-counter'),
		'parent'                => __('Parent Download',              'simple-download-counter'),
		'parent_item_colon'     => __('Parent Downloads:',            'simple-download-counter'),
		'not_found'             => __('No Downloads found.',          'simple-download-counter'),
		'not_found_in_trash'    => __('No Downloads found in Trash.', 'simple-download-counter'),
		'featured_image'        => __('Featured Image',               'simple-download-counter'),
		'set_featured_image'    => __('Set Featured Image',           'simple-download-counter'),
		'remove_featured_image' => __('Remove Featured Image',        'simple-download-counter'),
		'use_featured_image'    => __('Use as Featured Image',        'simple-download-counter'),
		'archives'              => __('Download Archives',            'simple-download-counter'),
		'insert_into_item'      => __('Insert into Download',         'simple-download-counter'),
		'uploaded_to_this_item' => __('Uploaded to this Download',    'simple-download-counter'),
		'filter_items_list'     => __('Filter Download list',         'simple-download-counter'),
		'items_list_navigation' => __('Download list navigation',     'simple-download-counter'),
		'items_list'            => __('Download list',                'simple-download-counter'),
		
	);
	
	$args = array(
		
		'labels'               => $labels,
		'label'                => __('Downloads', 'simple-download-counter'),
		'description'          => __('File Downloads', 'simple-download-counter'),
		'public'               => false,
		'hierarchical'         => false,
		'exclude_from_search'  => true,
		'publicly_queryable'   => true,
		'show_ui'              => true,
		'show_in_menu'         => true,
		'show_in_nav_menus'    => false,
		'show_in_admin_bar'    => false,
		'show_in_rest'         => false,
		'menu_position'        => null,
		'menu_icon'            => 'dashicons-download',
		'capability_type'      => 'post',
		'supports'             => array('author', 'thumbnail', 'title'),
		'register_meta_box_cb' => null,
		'taxonomies'           => array('sdc_download_category', 'sdc_download_tag'),
		'has_archive'          => false,
		'rewrite'              => array('slug' => simple_download_counter_key(), 'with_front' => true, 'feeds' => false, 'pages' => false, 'ep_mask' => EP_PERMALINK), // false
		'query_var'            => simple_download_counter_key(), // false
		'can_export'           => true,
		'delete_with_user'     => false,
		
	);
	
	$options = simple_download_counter_options();
	
	$custom_fields = (isset($options['custom_fields']) && !empty($options['custom_fields'])) ? true : false;
	
	if ($custom_fields) array_push($args['supports'], 'custom-fields');
	
	register_post_type('sdc_download', $args);
	
}


function simple_download_counter_custom_category() {
	
	$labels = array(
		
		'name'              => __('Categories',         'simple-download-counter'),
		'singular_name'     => __('Category',           'simple-download-counter'),
		'search_items'      => __('Search Categories',  'simple-download-counter'),
		'all_items'         => __('All Categories',     'simple-download-counter'),
		'parent_item'       => __('Parent Category',    'simple-download-counter'),
		'parent_item_colon' => __('Parent Category:',   'simple-download-counter'),
		'edit_item'         => __('Edit Category',      'simple-download-counter'),
		'update_item'       => __('Update Category',    'simple-download-counter'),
		'add_new_item'      => __('Add New Category',   'simple-download-counter'),
		'new_item_name'     => __('New Category Name',  'simple-download-counter'),
		'menu_name'         => __('Categories',         'simple-download-counter'),
		
	);
	
	$args = array(
		
		'labels'             => $labels,
		'description'        => __('Download Categories', 'simple-download-counter'),
		'public'             => false,
		'publicly_queryable' => false,
		'hierarchical'       => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => false,
		'show_in_rest'       => false,
		'show_tagcloud'      => false,
		'show_in_quick_edit' => true,
		'show_admin_column'  => true,
		'meta_box_cb'        => null,
		'rewrite'            => false,
		'query_var'          => true,
		'default_term'       => array('name' => __('Uncategorized', 'simple-download-counter'), 'slug' => 'uncategorized', 'description' => __('Default Download Category', 'simple-download-counter')),
		'sort'               => null,
		
	);
	
	register_taxonomy('sdc_download_category', 'sdc_download', $args);
	
}


function simple_download_counter_custom_tag() {
	
	$labels = array(
		
		'name'                       => __('Tags',                           'simple-download-counter'),
		'singular_name'              => __('Tag',                            'simple-download-counter'),
		'search_items'               => __('Search Tags',                    'simple-download-counter'),
		'popular_items'              => __('Popular Tags',                   'simple-download-counter'),
		'all_items'                  => __('All Tags',                       'simple-download-counter'),
		'edit_item'                  => __('Edit Tag',                       'simple-download-counter'),
		'update_item'                => __('Update Tag',                     'simple-download-counter'),
		'add_new_item'               => __('Add New Tag',                    'simple-download-counter'),
		'new_item_name'              => __('New Tag Name',                   'simple-download-counter'),
		'separate_items_with_commas' => __('Separate tags with commas',      'simple-download-counter'),
		'add_or_remove_items'        => __('Add or remove tags',             'simple-download-counter'),
		'choose_from_most_used'      => __('Choose from the most used tags', 'simple-download-counter'),
		'not_found'                  => __('No tags found.',                 'simple-download-counter'),
		'menu_name'                  => __('Tags',                           'simple-download-counter'),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		
	);
	
	$args = array(
		
		'labels'             => $labels,
		'description'        => __('Download Tags', 'simple-download-counter'),
		'public'             => false,
		'publicly_queryable' => false,
		'hierarchical'       => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => false,
		'show_in_rest'       => false,
		'show_tagcloud'      => false,
		'show_in_quick_edit' => true,
		'show_admin_column'  => false,
		'meta_box_cb'        => null,
		'rewrite'            => false,
		'query_var'          => true,
		'default_term'       => null,
		'sort'               => null,
		
	);
	
	register_taxonomy('sdc_download_tag', 'sdc_download', $args);
	
}