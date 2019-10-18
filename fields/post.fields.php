<?php
function post_type_menus() {
	$labels = array(
    	'name' => _x('Menus', 'post type general name', THEMEDOMAIN),
    	'singular_name' => _x('Menu', 'post type singular name', THEMEDOMAIN),
    	'add_new' => _x('Add New Menu', 'book', THEMEDOMAIN),
    	'add_new_item' => __('Add New Menu', THEMEDOMAIN),
    	'edit_item' => __('Edit Menu', THEMEDOMAIN),
    	'new_item' => __('New Menu', THEMEDOMAIN),
    	'view_item' => __('View Menu', THEMEDOMAIN),
    	'search_items' => __('Search Menus', THEMEDOMAIN),
    	'not_found' =>  __('No Menu found', THEMEDOMAIN),
    	'not_found_in_trash' => __('No Menu found in Trash', THEMEDOMAIN), 
    	'parent_item_colon' => ''
	);		
	$args = array(
    	'labels' => $labels,
    	'public' => true,
    	'publicly_queryable' => true,
    	'show_ui' => true, 
    	'query_var' => true,
    	'rewrite' => true,
    	'capability_type' => 'post',
    	'hierarchical' => false,
    	'menu_position' => null,
    	'supports' => array('title','editor', 'thumbnail', 'excerpt', 'revisions'),
    	'menu_icon' => ''
	); 		

	register_post_type( 'menus', $args );
	
  	$labels = array(			  
  	  'name' => _x( 'Menu Categories', 'taxonomy general name', THEMEDOMAIN ),
  	  'singular_name' => _x( 'Menu Category', 'taxonomy singular name', THEMEDOMAIN ),
  	  'search_items' =>  __( 'Search Menu Categories', THEMEDOMAIN ),
  	  'all_items' => __( 'All Menu Categories', THEMEDOMAIN ),
  	  'parent_item' => __( 'Parent Menu Category', THEMEDOMAIN ),
  	  'parent_item_colon' => __( 'Parent Menu Category:', THEMEDOMAIN ),
  	  'edit_item' => __( 'Edit Menu Category', THEMEDOMAIN ), 
  	  'update_item' => __( 'Update Menu Category', THEMEDOMAIN ),
  	  'add_new_item' => __( 'Add New Menu Category', THEMEDOMAIN ),
  	  'new_item_name' => __( 'New Menu Category Name', THEMEDOMAIN ),
  	); 							  
  	
  	register_taxonomy(
		'menucats',
		'menus',
		array(
			'public'=>true,
			'hierarchical' => true,
			'labels'=> $labels,
			'query_var' => 'menucats',
			'show_ui' => true,
			'rewrite' => array( 'slug' => 'menucats', 'with_front' => false ),
		)
	);		  
} 
								  
add_action('init', 'post_type_menus');

function post_type_events() {
	$labels = array(
    	'name' => _x('Events', 'post type general name', THEMEDOMAIN),
    	'singular_name' => _x('Event', 'post type singular name', THEMEDOMAIN),
    	'add_new' => _x('Add New Event', 'book', THEMEDOMAIN),
    	'add_new_item' => __('Add New Event', THEMEDOMAIN),
    	'edit_item' => __('Edit Event', THEMEDOMAIN),
    	'new_item' => __('New Event', THEMEDOMAIN),
    	'view_item' => __('View Event', THEMEDOMAIN),
    	'search_items' => __('Search Events', THEMEDOMAIN),
    	'not_found' =>  __('No Event found', THEMEDOMAIN),
    	'not_found_in_trash' => __('No Event found in Trash', THEMEDOMAIN), 
    	'parent_item_colon' => ''
	);		
	$args = array(
    	'labels' => $labels,
    	'public' => true,
    	'publicly_queryable' => true,
    	'show_ui' => true, 
    	'query_var' => true,
    	'rewrite' => true,
    	'capability_type' => 'post',
    	'hierarchical' => false,
    	'menu_position' => null,
    	'supports' => array('title','editor', 'thumbnail', 'comments'),
    	'menu_icon' => ''
	);
	
	$labels = array(			  
  	  'name' => _x( 'Event Categories', 'taxonomy general name', THEMEDOMAIN ),
  	  'singular_name' => _x( 'Event Category', 'taxonomy singular name', THEMEDOMAIN ),
  	  'search_items' =>  __( 'Search Event Categories', THEMEDOMAIN ),
  	  'all_items' => __( 'All Event Categories', THEMEDOMAIN ),
  	  'parent_item' => __( 'Parent Event Category', THEMEDOMAIN ),
  	  'parent_item_colon' => __( 'Parent Event Category:', THEMEDOMAIN ),
  	  'edit_item' => __( 'Edit Event Category', THEMEDOMAIN ), 
  	  'update_item' => __( 'Update Event Category', THEMEDOMAIN ),
  	  'add_new_item' => __( 'Add New Event Category', THEMEDOMAIN ),
  	  'new_item_name' => __( 'New Event Category Name', THEMEDOMAIN ),
  	); 							  
  	
  	register_taxonomy(
		'eventscats',
		'events',
		array(
			'public'=>true,
			'hierarchical' => true,
			'labels'=> $labels,
			'query_var' => 'eventscats',
			'show_ui' => true,
			'rewrite' => array( 'slug' => 'eventscats', 'with_front' => false ),
		)
	);

	register_post_type( 'events', $args );			  
} 
								  
add_action('init', 'post_type_events');

function post_type_galleries() {
	$labels = array(
    	'name' => _x('Galleries', 'post type general name', THEMEDOMAIN),
    	'singular_name' => _x('Gallery', 'post type singular name', THEMEDOMAIN),
    	'add_new' => _x('Add New Gallery', 'book', THEMEDOMAIN),
    	'add_new_item' => __('Add New Gallery', THEMEDOMAIN),
    	'edit_item' => __('Edit Gallery', THEMEDOMAIN),
    	'new_item' => __('New Gallery', THEMEDOMAIN),
    	'view_item' => __('View Gallery', THEMEDOMAIN),
    	'search_items' => __('Search Gallery', THEMEDOMAIN),
    	'not_found' =>  __('No Gallery found', THEMEDOMAIN),
    	'not_found_in_trash' => __('No Gallery found in Trash', THEMEDOMAIN), 
    	'parent_item_colon' => ''
	);		
	$args = array(
    	'labels' => $labels,
    	'public' => true,
    	'publicly_queryable' => true,
    	'show_ui' => true, 
    	'query_var' => true,
    	'rewrite' => true,
    	'capability_type' => 'post',
    	'hierarchical' => false,
    	'menu_position' => null,
    	'supports' => array('title','editor', 'thumbnail', 'excerpt'),
    	'menu_icon' => ''
	); 		

	register_post_type( 'galleries', $args );
	
  	$labels = array(			  
  	  'name' => _x( 'Gallery Categories', 'taxonomy general name', THEMEDOMAIN ),
  	  'singular_name' => _x( 'Gallery Category', 'taxonomy singular name', THEMEDOMAIN ),
  	  'search_items' =>  __( 'Search Gallery Categories', THEMEDOMAIN ),
  	  'all_items' => __( 'All Gallery Categories', THEMEDOMAIN ),
  	  'parent_item' => __( 'Parent Gallery Category', THEMEDOMAIN ),
  	  'parent_item_colon' => __( 'Parent Gallery Category:', THEMEDOMAIN ),
  	  'edit_item' => __( 'Edit Gallery Category', THEMEDOMAIN ), 
  	  'update_item' => __( 'Update Gallery Category', THEMEDOMAIN ),
  	  'add_new_item' => __( 'Add New Gallery Category', THEMEDOMAIN ),
  	  'new_item_name' => __( 'New Gallery Category Name', THEMEDOMAIN ),
  	); 							  
  	
  	register_taxonomy(
		'gallerycat',
		'galleries',
		array(
			'public'=>true,
			'hierarchical' => true,
			'labels'=> $labels,
			'query_var' => 'gallerycat',
			'show_ui' => true,
			'rewrite' => array( 'slug' => 'gallerycat', 'with_front' => false ),
		)
	);		  
} 
								  
add_action('init', 'post_type_galleries');


function post_type_testimonials() {
	$labels = array(
    	'name' => _x('Testimonials', 'post type general name', THEMEDOMAIN),
    	'singular_name' => _x('Testimonial', 'post type singular name', THEMEDOMAIN),
    	'add_new' => _x('Add New Testimonial', 'book', THEMEDOMAIN),
    	'add_new_item' => __('Add New Testimonial', THEMEDOMAIN),
    	'edit_item' => __('Edit Testimonial', THEMEDOMAIN),
    	'new_item' => __('New Testimonial', THEMEDOMAIN),
    	'view_item' => __('View Testimonial', THEMEDOMAIN),
    	'search_items' => __('Search Testimonial', THEMEDOMAIN),
    	'not_found' =>  __('No Testimonial found', THEMEDOMAIN),
    	'not_found_in_trash' => __('No Testimonial found in Trash', THEMEDOMAIN), 
    	'parent_item_colon' => ''
	);		
	$args = array(
    	'labels' => $labels,
    	'public' => true,
    	'publicly_queryable' => true,
    	'show_ui' => true, 
    	'query_var' => true,
    	'rewrite' => true,
    	'capability_type' => 'post',
    	'hierarchical' => false,
    	'menu_position' => null,
    	'supports' => array('title', 'editor', 'thumbnail'),
    	'menu_icon' => ''
	); 		

	register_post_type( 'testimonials', $args );
	
	$labels = array(			  
  	  'name' => _x( 'Testimonial Categories', 'taxonomy general name', THEMEDOMAIN ),
  	  'singular_name' => _x( 'Testimonial Category', 'taxonomy singular name', THEMEDOMAIN ),
  	  'search_items' =>  __( 'Search Testimonial Categories', THEMEDOMAIN ),
  	  'all_items' => __( 'All Testimonial Categories', THEMEDOMAIN ),
  	  'parent_item' => __( 'Parent Testimonial Category', THEMEDOMAIN ),
  	  'parent_item_colon' => __( 'Parent Testimonial Category:', THEMEDOMAIN ),
  	  'edit_item' => __( 'Edit Testimonial Category', THEMEDOMAIN ), 
  	  'update_item' => __( 'Update Testimonial Category', THEMEDOMAIN ),
  	  'add_new_item' => __( 'Add New Testimonial Category', THEMEDOMAIN ),
  	  'new_item_name' => __( 'New Testimonial Category Name', THEMEDOMAIN ),
  	); 							  
  	
  	register_taxonomy(
		'testimonialcats',
		'testimonials',
		array(
			'public'=>true,
			'hierarchical' => true,
			'labels'=> $labels,
			'query_var' => 'testimonialcats',
			'show_ui' => true,
			'rewrite' => array( 'slug' => 'testimonialcats', 'with_front' => false ),
		)
	);		  
} 
								  
add_action('init', 'post_type_testimonials');


function post_type_team() {
	$labels = array(
    	'name' => _x('Team Members', 'post type general name', THEMEDOMAIN),
    	'singular_name' => _x('Team Member', 'post type singular name', THEMEDOMAIN),
    	'add_new' => _x('Add New Team Member', 'book', THEMEDOMAIN),
    	'add_new_item' => __('Add New Team Member', THEMEDOMAIN),
    	'edit_item' => __('Edit Team Member', THEMEDOMAIN),
    	'new_item' => __('New Team Member', THEMEDOMAIN),
    	'view_item' => __('View Team Member', THEMEDOMAIN),
    	'search_items' => __('Search Team Members', THEMEDOMAIN),
    	'not_found' =>  __('No Team Member found', THEMEDOMAIN),
    	'not_found_in_trash' => __('No Team Member found in Trash', THEMEDOMAIN), 
    	'parent_item_colon' => ''
	);		
	$args = array(
    	'labels' => $labels,
    	'public' => true,
    	'publicly_queryable' => true,
    	'show_ui' => true, 
    	'query_var' => true,
    	'rewrite' => true,
    	'capability_type' => 'post',
    	'hierarchical' => false,
    	'menu_position' => null,
    	'supports' => array('title','editor', 'thumbnail'),
    	'menu_icon' => ''
	); 		

	register_post_type( 'team', $args );
	
	$labels = array(			  
  	  'name' => _x( 'Team Categories', 'taxonomy general name', THEMEDOMAIN ),
  	  'singular_name' => _x( 'Team Category', 'taxonomy singular name', THEMEDOMAIN ),
  	  'search_items' =>  __( 'Team Service Categories', THEMEDOMAIN ),
  	  'all_items' => __( 'All Team Categories', THEMEDOMAIN ),
  	  'parent_item' => __( 'Parent Team Category', THEMEDOMAIN ),
  	  'parent_item_colon' => __( 'Parent Team Category:', THEMEDOMAIN ),
  	  'edit_item' => __( 'Edit Team Category', THEMEDOMAIN ), 
  	  'update_item' => __( 'Update Team Category', THEMEDOMAIN ),
  	  'add_new_item' => __( 'Add New Team Category', THEMEDOMAIN ),
  	  'new_item_name' => __( 'New Team Category Name', THEMEDOMAIN ),
  	); 							  
  	
  	register_taxonomy(
		'teamcats',
		'team',
		array(
			'public'=>true,
			'hierarchical' => true,
			'labels'=> $labels,
			'query_var' => 'teamcats',
			'show_ui' => true,
			'rewrite' => array( 'slug' => 'teamcats', 'with_front' => false ),
		)
	);
}
add_action('init', 'post_type_team');


add_filter( 'manage_posts_columns', 'rt_add_gravatar_col');
function rt_add_gravatar_col($cols) {
	$cols['thumbnail'] = __('Thumbnail', THEMEDOMAIN);
	return $cols;
}

add_action( 'manage_posts_custom_column', 'rt_get_author_gravatar');
function rt_get_author_gravatar($column_name ) {
	if ( $column_name  == 'thumbnail'  ) {
		echo get_the_post_thumbnail(get_the_ID(), array(100, 100));
	}
}

/*
	Get gallery list
*/
$args = array(
    'numberposts' => -1,
    'post_type' => array('galleries'),
);

$galleries_arr = get_posts($args);
$galleries_select = array();
$galleries_select['(Display Post Featured Image)'] = '';
$galleries_select['(Hide Post Featured Image)'] = -1;

foreach($galleries_arr as $gallery)
{
	$galleries_select[$gallery->post_title] = $gallery->ID;
}

/*
	Get post layouts
*/
$post_layout_select = array();
$post_layout_select = array(
	'With Right Sidebar' => 'With Right Sidebar',
	'With Left Sidebar' => 'With Left Sidebar',
	'Fullwidth' => 'Fullwidth',
);

//Get all sidebars
$theme_sidebar = array(
	'' => '',
	'Page Sidebar' => 'Page Sidebar', 
	'Contact Sidebar' => 'Contact Sidebar', 
	'Blog Sidebar' => 'Blog Sidebar',
);

$dynamic_sidebar = get_option('pp_sidebar');

if(!empty($dynamic_sidebar))
{
	foreach($dynamic_sidebar as $sidebar)
	{
		$theme_sidebar[$sidebar] = $sidebar;
	}
}

/*
	Begin creating custom fields
*/

$postmetas = 
	array (
		'post' => array(
			array("section" => "Content Type", "id" => "post_layout", "type" => "select", "title" => "Post Layout", "description" => "You can select layout of this single post page.", "items" => $post_layout_select),
			array(
    		"section" => "Featured Content Type", "id" => "post_ft_type", "type" => "select", "title" => "Featured Content Type", "description" => "Select featured content type for this post. Different content type will be displayed on single post page", 
				"items" => array(
					"Image" => "Image",
					"Gallery" => "Gallery",
					"Vimeo Video" => "Vimeo Video",
					"Youtube Video" => "Youtube Video",
				)),
				
			array("section" => "Gallery", "id" => "post_ft_gallery", "type" => "select", "title" => "Gallery", "description" => "Please select a gallery (*Note enter if you select \"Gallery\" as Featured Content Type))", "items" => $galleries_select),
				
			array("section" => "Vimeo Video ID", "id" => "post_ft_vimeo", "type" => "text", "title" => "Vimeo Video ID", "description" => "Please enter Vimeo Video ID for example 73317780 (*Note enter if you select \"Vimeo Video\" as Featured Content Type)"),
			
			array("section" => "Youtube Video ID", "id" => "post_ft_youtube", "type" => "text", "title" => "Youtube Video ID", "description" => "Please enter Youtube Video ID for example 6AIdXisPqHc (*Note enter if you select \"Youtube Video\" as Featured Content Type)"),
		),
		
		'galleries' => array(
			array("section" => "Gallery Template", "id" => "gallery_template", "type" => "select", "title" => "Gallery Template", "description" => "Select gallery template for this gallery", 
				"items" => array(
					"Gallery 2 Columns" => "Gallery 2 Columns",
					"Gallery 3 Columns" => "Gallery 3 Columns",
					"Gallery 4 Columns" => "Gallery 4 Columns",
					"Gallery Fullscreen" => "Gallery Fullscreen",
					"Gallery Image Flow" => "Gallery Image Flow",
					"Gallery Kenburns" => "Gallery Kenburns",
				)),
			array("section" => "Password Protect", "id" => "gallery_password", "title" => "Password", "description" => "Enter your password for this gallery"),
			array("section" => "Background Audio", "id" => "gallery_audio", "type" => "file", "title" => "Gallery Background Audio", "description" => "Support file types *.mp3, *.mp4"),
			/*array("section" => "Select Sidebar", "id" => "gallery_sidebar", "type" => "select", "title" => "Gallery Sidebar", "description" => "Select this page's sidebar to display (only gallery sidebar templates support)", "items" => $theme_sidebar),*/
			array("section" => "Menu", "id" => "post_menu_transparent", "type" => "checkbox", "title" => "Make Menu Transparent", "description" => "Check this option if you want to display menu in transparent (support only when you upload gallery page header image using set featured image box)"),
		),
		
		'team' => array(
			array("section" => "Team Option", "id" => "team_position", "type" => "text", "title" => "Position and Role", "description" => "Enter team member position and role ex. Marketing Manager"),
			array("section" => "Facebook URL", "id" => "member_facebook", "type" => "text", "title" => "Facebook URL", "description" => "Enter team member Facebook URL"),
		    array("section" => "Twitter URL", "id" => "member_twitter", "type" => "text", "title" => "Twitter URL", "description" => "Enter team member Twitter URL"),
		    array("section" => "Google+ URL", "id" => "member_google", "type" => "text", "title" => "Google+ URL", "description" => "Enter team member Google+ URL"),
		    array("section" => "Linkedin URL", "id" => "member_linkedin", "type" => "text", "title" => "Linkedin URL", "description" => "Enter team member Linkedin URL"),
		),
		
		'testimonials' => array(
			array("section" => "Testimonial Option", "id" => "testimonial_name", "type" => "text", "title" => "Customer Name", "description" => "Enter customer name"),
			array("section" => "Testimonial Option", "id" => "testimonial_position", "type" => "text", "title" => "Customer Position", "description" => "Enter customer position in company"),
			array("section" => "Testimonial Option", "id" => "testimonial_company_name", "type" => "text", "title" => "Company Name", "description" => "Enter customer company name"),
			array("section" => "Testimonial Option", "id" => "testimonial_company_website", "type" => "text", "title" => "Company Website URL", "description" => "Enter customer company website URL"),
		),
		
		'events' => array(
		    array("section" => "Date", "id" => "event_date", "type" => "date", "title" => "Event Date", "description" => "Select date for this event"),
		    array("section" => "Date", "id" => "event_date_raw", "type" => "date_raw", "title" => "Event Date Raw", "description" => "Select date for this event"),
		    array("section" => "Time", "id" => "event_from_time", "type" => "time", "title" => "From", "description" => "Select begin time for this event"),
		    array("section" => "Time", "id" => "event_to_time", "type" => "time", "title" => "To", "description" => "Select end time for this event"),
		    
		    array("section" => "Description", "id" => "event_description", "type" => "textarea", "title" => "Event Short Info", "description" => "Enter short description about this event ex. website, rules etc. It displays in sidebar of single event page (HTML support)"),
		),
		
		'menus' => array(
			array("section" => "Menu Option", "id" => "menu_price", "type" => "text", "title" => "Price", "description" => "Enter menu price (in number)"),
			array("section" => "Menu Option", "id" => "menu_price_currency", "type" => "text", "title" => "Price Currency", "description" => "Enter menu price currency ex. $"),
		    array("section" => "Menu Option", "id" => "menu_highlight", "type" => "checkbox", "title" => "Highlight This Menu", "description" => "Check this option to highlight this menu"),
		    array("section" => "Menu Option", "id" => "menu_highlight_title", "type" => "text", "title" => "Highlight Title", "description" => "Enter Highlight Title ex. recommended, best selling"),
		),
);

//Check if enable featured image as background
$pp_blog_ft_bg = get_option('pp_blog_ft_bg');

if(!empty($pp_blog_ft_bg))
{
	$postmetas['post'][] = array("section" => "Slider", "id" => "post_menu_transparent", "type" => "checkbox", "title" => "Make Main Menu Transparent", "description" => "Check this option if you want to display menu in transparent for this post");
}

//Check if enable event featured image as background
$pp_event_ft_bg = get_option('pp_event_ft_bg');

if(!empty($pp_event_ft_bg))
{
	$postmetas['events'][] = array("section" => "Slider", "id" => "post_menu_transparent", "type" => "checkbox", "title" => "Make Main Menu Transparent", "description" => "Check this option if you want to display menu in transparent for this event");
}

function create_meta_box() {

	global $postmetas;
	
	if(!isset($_GET['post_type']) OR empty($_GET['post_type']))
	{
		if(isset($_GET['post']) && !empty($_GET['post']))
		{
			$post_obj = get_post($_GET['post']);
			$_GET['post_type'] = $post_obj->post_type;
		}
		else
		{
			$_GET['post_type'] = 'post';
		}
	}
	
	if ( function_exists('add_meta_box') && isset($postmetas) && count($postmetas) > 0 ) {  
		foreach($postmetas as $key => $postmeta)
		{
			if($_GET['post_type']==$key && !empty($postmeta))
			{
				if($key != 'pricing')
				{
					add_meta_box( 'metabox', ucfirst($key).' Options', 'new_meta_box', $key, 'side', 'high' );
				}
				else
				{
					add_meta_box( 'metabox', ucfirst($key).' Options', 'new_meta_box', $key, 'normal', 'high' );
				}
			}
		}
	}

}  

function new_meta_box() {
	global $post, $postmetas;
	
	if(!isset($_GET['post_type']) OR empty($_GET['post_type']))
	{
		if(isset($_GET['post']) && !empty($_GET['post']))
		{
			$post_obj = get_post($_GET['post']);
			$_GET['post_type'] = $post_obj->post_type;
		}
		else
		{
			$_GET['post_type'] = 'post';
		}
	}

	echo '<input type="hidden" name="tg_custom_post_flag" id="tg_custom_post_flag" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	$meta_section = '';

	foreach ( $postmetas as $key => $postmeta ) {
	
		if($_GET['post_type'] == $key)
		{
		
			foreach ( $postmeta as $postmeta_key => $each_meta ) {
		
				$meta_id = $each_meta['id'];
				$meta_title = $each_meta['title'];
				$meta_description = $each_meta['description'];
				
				if(isset($postmeta['section']))
				{
					$meta_section = $postmeta['section'];
				}
				
				$meta_type = '';
				if(isset($each_meta['type']))
				{
					$meta_type = $each_meta['type'];
				}
				
				echo "<br/><div class='meta_$meta_type'><strong>".$meta_title."</strong><hr class='pp_widget_hr'/>";
				echo "<div class='pp_widget_description'>$meta_description</div>";
				
				if ($meta_type == 'checkbox') {
					$checked = get_post_meta($post->ID, $meta_id, true) == '1' ? "checked" : "";
					echo "<br style='clear:both'><input type='checkbox' name='$meta_id' id='$meta_id' class='iphone_checkboxes' value='1' $checked /><br style='clear:both'><br/><br/>";
				}
				else if ($meta_type == 'select') {
					echo "<p><select name='$meta_id' id='$meta_id'>";
					
					if(!empty($each_meta['items']))
					{
						foreach ($each_meta['items'] as $key => $item)
						{
							echo '<option value="'.$item.'"';
							
							if($item == get_post_meta($post->ID, $meta_id, true))
							{
								echo ' selected ';
							}
							
							echo '>'.$key.'</option>';
						}
					}
					
					echo "</select></p>";
				}
				else if ($meta_type == 'file') { 
				    echo "<p><input type='text' name='$meta_id' id='$meta_id' class='' value='".get_post_meta($post->ID, $meta_id, true)."' style='width:89%' /><input id='".$meta_id."_button' name='".$meta_id."_button' type='button' value='Upload' class='metabox_upload_btn button' readonly='readonly' rel='".$meta_id."' style='margin:7px 0 0 5px' /></p>";
				}
				else if ($meta_type == 'date') { 
					echo "<p><input type='text' name='$meta_id' id='$meta_id' class='pp_date' value='".get_post_meta($post->ID, $meta_id, true)."' style='width:99%' /></p>";
				}
				else if ($meta_type == 'date_raw') { 
					echo "<p><input id='".$meta_id."' name='".$meta_id."' type='text' value='".get_post_meta($post->ID, $meta_id, true)."' /></p>";
				}
				else if ($meta_type == 'time') { 
					echo "<p><input type='text' name='$meta_id' id='$meta_id' class='pp_time' value='".get_post_meta($post->ID, $meta_id, true)."' style='width:99%' /></p>";
				}
				else if ($meta_type == 'textarea') {
					if(isset($postmeta[$postmeta_key]['sample']))
					{
						echo "<p><textarea name='$meta_id' id='$meta_id' class=' hint' style='width:100%' rows='7' title='".$postmeta[$postmeta_key]['sample']."'>".get_post_meta($post->ID, $meta_id, true)."</textarea></p>";
					}
					else
					{
						echo "<p><textarea name='$meta_id' id='$meta_id' class='' style='width:100%' rows='7'>".get_post_meta($post->ID, $meta_id, true)."</textarea></p>";
					}
				}			
				else {
					if(isset($postmeta[$postmeta_key]['sample']))
					{
						echo "<p><input type='text' name='$meta_id' id='$meta_id' class='' title='".$postmeta[$postmeta_key]['sample']."' value='".get_post_meta($post->ID, $meta_id, true)."' style='width:99%' /></p>";
					}
					else
					{
						echo "<p><input type='text' name='$meta_id' id='$meta_id' class='' value='".get_post_meta($post->ID, $meta_id, true)."' style='width:99%' /></p>";
					}
				}
				
				echo '</div>';
			}
		}
	}
	
	echo '<br/>';

}

function save_postdata( $post_id ) {

	global $postmetas;

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times

	if ( isset($_POST['tg_custom_post_flag'])) 
	{

		// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
	
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
	
		// Check permissions
	
		if ( isset($_POST['post_type']) && 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) )
				return $post_id;
			} else {
			if ( !current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}
	
		// OK, we're authenticated
	
		if ( $parent_id = wp_is_post_revision($post_id) )
		{
			$post_id = $parent_id;
		}
		
		foreach ( $postmetas as $postmeta ) {
			foreach ( $postmeta as $each_meta ) {
		
				if (isset($_POST[$each_meta['id']]) && $_POST[$each_meta['id']]) {
					update_custom_meta($post_id, $_POST[$each_meta['id']], $each_meta['id']);
				}
				
				if (isset($_POST[$each_meta['id']]) && $_POST[$each_meta['id']] == "") {
					delete_post_meta($post_id, $each_meta['id']);
				}
				
				if (!isset($_POST[$each_meta['id']])) {
					delete_post_meta($post_id, $each_meta['id']);
				}
			
			}
		}
		
	}

}

function update_custom_meta($postID, $newvalue, $field_name) {

	if (!get_post_meta($postID, $field_name)) {
		add_post_meta($postID, $field_name, $newvalue);
	} else {
		update_post_meta($postID, $field_name, $newvalue);
	}

}

//init

add_action('admin_menu', 'create_meta_box'); 
add_action('save_post', 'save_postdata');  

/*
	End creating custom fields
*/

?>