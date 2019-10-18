<?php
//Get all galleries
$args = array(
    'numberposts' => -1,
    'post_type' => array('galleries'),
);

$galleries_arr = get_posts($args);
$galleries_select = array();
$galleries_select[''] = '';

foreach($galleries_arr as $gallery)
{
    $galleries_select[$gallery->ID] = $gallery->post_title;
}

//Get all categories
$categories_arr = get_categories();
$categories_select = array();
$categories_select[''] = '';

foreach ($categories_arr as $cat) {
	$categories_select[$cat->cat_ID] = $cat->cat_name;
}

//Get all menu_cats
$menu_cats_arr = get_terms('menucats', 'hide_empty=0&hierarchical=0&parent=0&orderby=menu_order');
$menu_cats_select = array();
$menu_cats_select[''] = '';

foreach ($menu_cats_arr as $set) {
	$menu_cats_select[$set->slug] = $set->name;
}

//Get all team categories
$team_cats_arr = get_terms('teamcats', 'hide_empty=0&hierarchical=0&parent=0&orderby=menu_order');
$team_cats_select = array();
$team_cats_select[''] = '';

foreach ($team_cats_arr as $team_cat) {
	$team_cats_select[$team_cat->slug] = $team_cat->name;
}

//Get all testimonials categories
$testimonial_cats_arr = get_terms('testimonialcats', 'hide_empty=0&hierarchical=0&parent=0&orderby=menu_order');
$testimonial_cats_select = array();
$testimonial_cats_select[''] = '';

foreach ($testimonial_cats_arr as $testimonial_cat) {
	$testimonial_cats_select[$testimonial_cat->slug] = $testimonial_cat->name;
}

//Get order options
$order_select = array(
	'default' 	=> 'By Default',
	'newest'	=> 'By Newest',
	'oldest'	=> 'By Oldest',
	'title'		=> 'By Title',
	'random'	=> 'By Random',
);

//Get column options
$column_select = array(
	'1' => '1 Column',
	'2' => '2 Columns',
	'3'	=> '3 Columns',
	'4'	=> '4 Columns',
);

//Get column options
$team_column_select = array(
	'2' => '2 Columns',
	'3'	=> '3 Columns',
	'4'	=> '4 Columns',
);

$gallery_column_select = array(
	'3'	=> '3 Columns',
	'4'	=> '4 Columns',
);

$menu_simple_column_select = array(
	'1'	=> '1 Column',
	'2'	=> '2 Columns',
	'3'	=> '3 Columns',
);

$menu_column_select = array(
	'2'	=> '2 Columns',
	'3'	=> '3 Columns',
	'4'	=> '4 Columns',
);

$menu_layout_select = array(
	'fullwidth'	=> 'Fullwidth',
	'fixedwidth'=> 'Fixed Width',
);

$gallery_layout_select = array(
	'fullwidth'	=> 'Fullwidth',
	'fixedwidth'=> 'Fixed Width',
);

//Get parallax type options
$parallax_select = array(
	'' 	=> 'None',
	'scroll_pos'   => 'Scroll Position',
);

$background_size_select = array(
	'' 	=> 'Cover',
	'110' 	=> '110%',
	'120' 	=> '120%',
	'130' 	=> '130%',
	'140' 	=> '140%',
	'150' 	=> '150%',
	'160' 	=> '160%',
	'170' 	=> '170%',
	'180' 	=> '180%',
	'190' 	=> '190%',
	'200' => '200%',
);

$ppb_shortcodes = array(
    'ppb_text' => array(
    	'title' =>  'Text Block',
    	'attr' => array(
    		'background' => array(
    			'title' => 'Background Image',
    			'type' => 'file',
    			'desc' => 'Upload background image you want to display for this content',
    		),
    		'background_parallax' => array(
    			'title' => 'Background Parallax Option',
    			'type' => 'select',
    			'options' => $parallax_select,
    			'desc' => 'You can choose parallax type for this content background. Select none to disable parallax',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_divider' => array(
    	'title' =>  'Divider',
    	'attr' => array(),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_menu_simple' => array(
    	'title' =>  'Menu Simple',
    	'attr' => array(
    		'cat' => array(
    			'title' => 'Filter by menu category',
    			'type' => 'select',
    			'options' => $menu_cats_select,
    			'desc' => 'You can choose to display only some menu items from selected menu category',
    		),
    		'columns' => array(
    			'title' => 'Columns',
    			'type' => 'select',
    			'options' => $menu_simple_column_select,
    			'desc' => 'Select how many columns you want to display menu items in a row',
    		),
    		'order' => array(
    			'title' => 'Order By',
    			'type' => 'select',
    			'options' => $order_select,
    			'desc' => 'Select how you want to order menu items',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_menu_card' => array(
    	'title' =>  'Menu Card',
    	'attr' => array(
    		'cat' => array(
    			'title' => 'Filter by menu category',
    			'type' => 'select',
    			'options' => $menu_cats_select,
    			'desc' => 'You can choose to display only some menu items from selected menu category',
    		),
    		'columns' => array(
    			'title' => 'Columns',
    			'type' => 'select',
    			'options' => $menu_column_select,
    			'desc' => 'Select how many columns you want to display menu items in a row',
    		),
    		'order' => array(
    			'title' => 'Order By',
    			'type' => 'select',
    			'options' => $order_select,
    			'desc' => 'Select how you want to order menu items',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_menu_classic' => array(
    	'title' =>  'Menu Classic',
    	'attr' => array(
    		'cat' => array(
    			'title' => 'Filter by menu category',
    			'type' => 'select',
    			'options' => $menu_cats_select,
    			'desc' => 'You can choose to display only some menu items from selected menu category',
    		),
    		'columns' => array(
    			'title' => 'Columns',
    			'type' => 'select',
    			'options' => $menu_column_select,
    			'desc' => 'Select how many columns you want to display menu items in a row',
    		),
    		'order' => array(
    			'title' => 'Order By',
    			'type' => 'select',
    			'options' => $order_select,
    			'desc' => 'Select how you want to order menu items',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_gallery' => array(
    	'title' =>  'Gallery',
    	'attr' => array(
    		'gallery' => array(
    			'title' => 'Gallery',
    			'type' => 'select',
    			'options' => $galleries_select,
    			'desc' => 'Select the gallery you want to display',
    		),
    		'columns' => array(
    			'title' => 'Columns',
    			'type' => 'select',
    			'options' => $gallery_column_select,
    			'desc' => 'Select how many columns you want to display gallery images in a row',
    		),
    		/*'background' => array(
    			'title' => 'Background Image',
    			'type' => 'file',
    			'desc' => 'Upload background image you want to display for this content',
    		),*/
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_blog' => array(
    	'title' =>  'Blog Grid',
    	'attr' => array(
    		'category' => array(
    			'title' => 'Filter by category',
    			'type' => 'select',
    			'options' => $categories_select,
    			'desc' => 'You can choose to display only some posts from selected category',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_blog_carousel' => array(
    	'title' =>  'Blog Carousel',
    	'attr' => array(
    		'category' => array(
    			'title' => 'Filter by category',
    			'type' => 'select',
    			'options' => $categories_select,
    			'desc' => 'You can choose to display only some posts from selected category',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_transparent_video_bg' => array(
    	'title' =>  'Transparent Video Background',
    	'attr' => array(
    		'description' => array(
    			'type' => 'textarea',
    			'desc' => 'Enter short description. It displays under the title',
    		),
    		'mp4_video_url' => array(
    			'title' => 'MP4 Video URL',
    			'type' => 'file',
    			'desc' => 'Upload .mp4 video file you want to display for this content',
    		),
    		'webm_video_url' => array(
    			'title' => 'WebM Video URL',
    			'type' => 'file',
    			'desc' => 'Upload .webm video file you want to display for this content',
    		),
    		'preview_img' => array(
    			'title' => 'Preview Image URL',
    			'type' => 'file',
    			'desc' => 'Upload preview image for this video',
    		),
    		'height' => array(
    			'type' => 'text',
    			'desc' => 'Enter number of height for background image (in pixel)',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_fullwidth_button' => array(
    	'title' =>  'Full Width Button',
    	'attr' => array(
    		'link_url' => array(
    			'type' => 'text',
    			'desc' => 'Enter redirected link URL when button is clicked',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_map' => array(
    	'title' =>  'Map',
    	'attr' => array(
    		'height' => array(
    			'type' => 'text',
    			'desc' => 'Enter map height (in pixels). Default 400px',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_team' => array(
    	'title' =>  'Team',
    	'attr' => array(
    		'columns' => array(
    			'title' => 'Columns',
    			'type' => 'select',
    			'options' => $team_column_select,
    			'desc' => 'Select how many columns you want to display team member items in a row',
    		),
    		'cat' => array(
    			'title' => 'Filter by team category',
    			'type' => 'select',
    			'options' => $team_cats_select,
    			'desc' => 'You can choose to display only some team members from selected team category',
    		),
    		'order' => array(
    			'title' => 'Order By',
    			'type' => 'select',
    			'options' => $order_select,
    			'desc' => 'Select how you want to order team members',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'background' => array(
    			'title' => 'Background Image',
    			'type' => 'file',
    			'desc' => 'Upload background image you want to display for this content',
    		),
    		'background_parallax' => array(
    			'title' => 'Background Parallax Option',
    			'type' => 'select',
    			'options' => $parallax_select,
    			'desc' => 'You can choose parallax type for this content background. Select none to disable parallax',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_testimonial' => array(
    	'title' =>  'Testimonials',
    	'attr' => array(
    		'cat' => array(
    			'title' => 'Filter by testimonials category',
    			'type' => 'select',
    			'options' => $testimonial_cats_select,
    			'desc' => 'You can choose to display only some testimonials from selected testimonial category',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'background' => array(
    			'title' => 'Background Image',
    			'type' => 'file',
    			'desc' => 'Upload background image you want to display for this content',
    		),
    		'background_parallax' => array(
    			'title' => 'Background Parallax Option',
    			'type' => 'select',
    			'options' => $parallax_select,
    			'desc' => 'You can choose parallax type for this content background. Select none to disable parallax',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_contact' => array(
    	'title' =>  'Contact Form',
    	'attr' => array(
    		'address' => array(
    			'title' => 'Address Info',
    			'type' => 'textarea',
    			'desc' => 'Enter company address, email etc. HTML and shortcode are support',
    		),
    		'background' => array(
    			'title' => 'Background Image',
    			'type' => 'file',
    			'desc' => 'Upload background image you want to display for this content',
    		),
    		'background_parallax' => array(
    			'title' => 'Background Parallax Option',
    			'type' => 'select',
    			'options' => $parallax_select,
    			'desc' => 'You can choose parallax type for this content background. Select none to disable parallax',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
);

ksort($ppb_shortcodes);
?>