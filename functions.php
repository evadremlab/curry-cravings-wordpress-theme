<?php
//Get theme data
$theme_obj = wp_get_theme('capella');

define("THEMENAME", $theme_obj['Name']);
if (!defined('THEMEDEMO'))
{
	define("THEMEDEMO", false);
}
define("SHORTNAME", "pp");
define("SKINSHORTNAME", "ps");
define("THEMEVERSION", $theme_obj['Version']);
define("THEMEDOMAIN", 'capella');
define("THEMEDEMOURL", $theme_obj['ThemeURI']);
define("THEMEDATEFORMAT", get_option('date_format'));
define("THEMETIMEFORMAT", get_option('time_format'));

//Get default WP uploads folder
$wp_upload_arr = wp_upload_dir();
define("THEMEUPLOAD", $wp_upload_arr['basedir']."/".strtolower(THEMENAME)."/");
define("THEMEUPLOADURL", $wp_upload_arr['baseurl']."/".strtolower(THEMENAME)."/");

//Define include fields from skin option
$pp_include_from_skin_arr = array(SHORTNAME.'_menu_font_color', SHORTNAME.'_menu_hover_font_color', SHORTNAME.'_menu_active_font_color', SHORTNAME.'_menu_bg_color', SHORTNAME.'_menu_border_color', SHORTNAME.'_menu_opacity_color', SHORTNAME.'_submenu_font_color', SHORTNAME.'_submenu_hover_font_color', SHORTNAME.'_submenu_bg_color', SHORTNAME.'_content_bg_color', SHORTNAME.'_font_color', SHORTNAME.'_link_color', SHORTNAME.'_hover_link_color', SHORTNAME.'_h1_font_color', SHORTNAME.'_tagline_font_color', SHORTNAME.'_hr_color', SHORTNAME.'_blog_date_bg', SHORTNAME.'_blog_date_color', SHORTNAME.'_blog_date_border', SHORTNAME.'_sidebar_font_color', SHORTNAME.'_sidebar_link_color', SHORTNAME.'_sidebar_hover_link_color', SHORTNAME.'_footer_bg_color', SHORTNAME.'_footer_font_color', SHORTNAME.'_footer_link_color', SHORTNAME.'_footer_hover_link_color', SHORTNAME.'_input_bg_color', SHORTNAME.'_input_font_color', SHORTNAME.'_input_border_color', SHORTNAME.'_input_focus_border_color', SHORTNAME.'_button_bg_color', SHORTNAME.'_button_font_color', SHORTNAME.'_button_border_color', SHORTNAME.'_footer_header_color');

/**
*	Defined all custom font elements
**/
$gg_fonts = array(SHORTNAME.'_menu_font', SHORTNAME.'_header_font', SHORTNAME.'_body_font', SHORTNAME.'_sidebar_title_font', SHORTNAME.'_button_font', SHORTNAME.'_post_meta_font', SHORTNAME.'_page_title_font', SHORTNAME.'_event_date_font');
global $gg_fonts;

load_theme_textdomain( THEMEDOMAIN, get_template_directory().'/languages' );

$locale = get_locale();
$locale_file = get_template_directory()."/languages/$locale.php";

if ( is_readable($locale_file) )
{
	require_once($locale_file);
}

//If restore default theme settings
if(is_admin() && isset($_POST['pp_restore_flg']) && !empty($_POST['pp_restore_flg']) && $_GET["page"] == "functions.php")
{
	global $wpdb;
	
	//Inject SQL for default setting
	include_once(get_template_directory() . "/restore.php");
}

//If clear cache
if(is_admin() && isset($_POST['method']) && !empty($_POST['method']) && $_POST['method'] == 'clear_cache')
{
	if(file_exists(get_template_directory()."/cache/combined.js"))
	{
		unlink(get_template_directory()."/cache/combined.js");
	}
	
	if(file_exists(get_template_directory()."/cache/combined.css"))
	{
		unlink(get_template_directory()."/cache/combined.css");
	}
	
	exit;
}

//If import default settings
if(is_admin() && isset($_POST['pp_import_default']) && !empty($_POST['pp_import_default']))
{
	global $wpdb;
	
	$default_json_settings = get_template_directory().'/cache/demos/demo.json';

	if(file_exists($default_json_settings))
    {
    	$import_options_json = file_get_contents($default_json_settings);
		$import_options_arr = json_decode($import_options_json, true);
		
		if(!empty($import_options_arr) && is_array($import_options_arr))
		{	
			foreach($import_options_arr as $key => $import_option)
			{	
				$wpdb->query("DELETE FROM `".$wpdb->prefix."options` WHERE option_name = '".$key."'");
				
				$wpdb->query("INSERT IGNORE INTO `".$wpdb->prefix."options` (`option_name`, `option_value`, `autoload`) VALUES('".$key."', '".$import_option."', 'yes');");
			}
		}
    }
	
	header("Location: admin.php?page=functions.php&saved=true".$_REQUEST['current_tab']);
	exit;
}

//If import settings
if(is_admin() && isset($_FILES['pp_import_current']["tmp_name"]) && !empty($_FILES['pp_import_current']["tmp_name"]))
{
	global $wpdb;
	
	$import_options_json = file_get_contents($_FILES["pp_import_current"]["tmp_name"]);
	$import_options_arr = json_decode($import_options_json, true);
	
	if(!empty($import_options_arr) && is_array($import_options_arr))
	{	
		foreach($import_options_arr as $key => $import_option)
		{	
			$wpdb->query("DELETE FROM `".$wpdb->prefix."options` WHERE option_name = '".$key."'");
			
			$wpdb->query("INSERT IGNORE INTO `".$wpdb->prefix."options` (`option_name`, `option_value`, `autoload`) VALUES('".$key."', '".$import_option."', 'yes');");
		}
	}
	
	header("Location: admin.php?page=functions.php&saved=true".$_REQUEST['current_tab']);
	exit;
}

//If export settings
if(is_admin() && isset($_POST['pp_export_current']) && !empty($_POST['pp_export_current']))
{
	$json_file_name = 'NebulaTheme_Export_'.date('m-d-Y_hia');

	header('Content-disposition: attachment; filename='.$json_file_name.'.json');
	header('Content-type: application/json');
	
	/**
	*	Setup admin setting
	**/
	include (get_template_directory() . "/lib/admin.lib.php");

	$export_options_arr = array();
	
	if(isset($options) && !empty($options) && is_array($options))
	{
		foreach ($options as $value) 
		{
			if(isset($value['id']) && !empty($value['id']))
			{ 
				$export_options_arr[$value['id']] = get_option($value['id']);
			}
		}
	}

	echo json_encode($export_options_arr);
	
	exit;
}

//If delete sidebar
if(is_admin() && isset($_POST['sidebar_id']) && !empty($_POST['sidebar_id']))
{
	$current_sidebar = get_option('pp_sidebar');
	
	if(isset($current_sidebar[ $_POST['sidebar_id'] ]))
	{
		unset($current_sidebar[ $_POST['sidebar_id'] ]);
		update_option( "pp_sidebar", $current_sidebar );
	}
	
	echo 1;
	exit;
}

//If delete ggfont
if(is_admin() && isset($_POST['ggfont']) && !empty($_POST['ggfont']))
{
	$current_ggfont = get_option('pp_ggfont');
	
	if(isset($current_ggfont[ $_POST['ggfont'] ]))
	{
		unset($current_ggfont[ $_POST['ggfont'] ]);
		update_option( "pp_ggfont", $current_ggfont );
	}
	
	echo 1;
	exit;
}

//If delete image
if(is_admin() && isset($_POST['field_id']) && !empty($_POST['field_id']) && isset($_GET["page"]) && $_GET["page"] == "functions.php" )
{
	$current_val = get_option($_POST['field_id']);
	delete_option( $_POST['field_id'] );
	
	echo 1;
	exit;
}

if ( function_exists( 'add_theme_support' ) ) {
	// Setup thumbnail support
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-background' );
	add_theme_support( 'post-formats', array( 'link', 'quote' ) );
	add_theme_support( 'woocommerce' );
}

if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'gallery_2', 490, 314, true );
	add_image_size( 'team_member', 456, 456, true );
	add_image_size( 'blog', 450, 500, true );
	add_image_size( 'blog_l', 960, 500, true );
	add_image_size( 'related_post', 190, 140, true );
}

/**
*	Setup all theme's library
**/

/**
*	Setup admin setting
**/
include (get_template_directory() . "/lib/admin.lib.php");
include (get_template_directory() . "/lib/menu.lib.php");
include (get_template_directory() . "/lib/twitter.lib.php");

if( ! class_exists( 'CSSMin', false ) )
{
	include (get_template_directory() . "/lib/cssmin.lib.php");
}

include (get_template_directory() . "/lib/jsmin.lib.php");

/**
*	Setup Sidebar
**/
include (get_template_directory() . "/lib/sidebar.lib.php");


//Get custom function
include (get_template_directory() . "/lib/custom.lib.php");


// Get Content Builder Module
include (get_template_directory() . "/lib/contentbuilder.lib.php");


//Get custom shortcode
include (get_template_directory() . "/lib/shortcode.lib.php");


// Setup theme custom widgets
include (get_template_directory() . "/lib/widgets.lib.php");

include (get_template_directory() . "/fields/post.fields.php");
include (get_template_directory() . "/fields/page.fields.php");
include (get_template_directory() . "/fields/gallery/tg-gallery.php");


/**
*	Setup AJAX search function
**/
add_action('wp_ajax_pp_ajax_search', 'pp_ajax_search');
add_action('wp_ajax_nopriv_pp_ajax_search', 'pp_ajax_search');

function pp_ajax_search() {
	global $wpdb;
	
	if (strlen($_POST['s'])>0) {
		$limit=5;
		$s=strtolower(addslashes($_POST['s']));
		$querystr = "
			SELECT $wpdb->posts.*
			FROM $wpdb->posts
			WHERE 1=1 AND ((lower($wpdb->posts.post_title) like '%$s%'))
			AND $wpdb->posts.post_type IN ('post', 'page', 'attachment', 'portfolios', 'galleries')
			AND (post_status = 'publish')
			ORDER BY $wpdb->posts.post_date DESC
			LIMIT $limit;
		 ";

	 	$pageposts = $wpdb->get_results($querystr, OBJECT);
	 	
	 	if(!empty($pageposts))
	 	{
			echo '<ul>';
	
	 		foreach($pageposts as $result_item) 
	 		{
	 			$post=$result_item;
	 			
	 			$post_type = get_post_type($post->ID);
				$post_type_class = 'fa-file-text-o';
				$post_type_title = '';
				
				switch($post_type)
				{
				    case 'galleries':
				    	$post_type_class = 'fa-picture-o';
				    	$post_type_title = __( 'Gallery', THEMEDOMAIN );
				    break;
				    
				    case 'page':
				    default:
				    	$post_type_class = 'fa-file-text-o';
				    	$post_type_title = __( 'Page', THEMEDOMAIN );
				    break;
				    
				    case 'portfolios':
				    	$post_type_class = 'fa-folder-open-o';
				    	$post_type_title = __( 'Portfolio', THEMEDOMAIN );
				    break;
				    
				    case 'services':
				    	$post_type_class = 'fa-star';
				    	$post_type_title = __( 'Service', THEMEDOMAIN );
				    break;
				    
				    case 'clients':
				    	$post_type_class = 'fa-user';
				    	$post_type_title = __( 'Client', THEMEDOMAIN );
				    break;
				}
	 			
				echo '<li>';
				echo '<div class="post_type_icon">';
				echo '<a href="'.get_permalink($post->ID).'"><i class="fa '.$post_type_class.'"></i></a>';
				echo '</div>';
				echo '<div class="ajax_post">';
				echo '<a href="'.get_permalink($post->ID).'"><strong>'.$post->post_title.'</strong><br/>';
				echo '<span class="post_attribute">'.date('M j, Y', strtotime($post->post_date)).'</span></a>';
				echo '</div>';
				echo '</li>';
			}
			
			echo '<li class="view_all"><a href="javascript:jQuery(\'#searchform\').submit()">'.__( 'View all results', THEMEDOMAIN ).'</a></li>';
	
			echo '</ul>';
		}

	}
	else 
	{
		echo '';
	}
	die();

}

/**
*	Setup contact form mailing function
**/
add_action('wp_ajax_pp_contact_mailer', 'pp_contact_mailer');
add_action('wp_ajax_nopriv_pp_contact_mailer', 'pp_contact_mailer');

function pp_contact_mailer() {
	check_ajax_referer( 'tgajax-post-contact-nonce', 'tg_security' );
	
	//Error message when message can't send
	define('ERROR_MESSAGE', 'Oops! something went wrong, please try to submit later.');
	
	if (isset($_POST['your_name'])) {
	
		//Get your email address
		$contact_email = get_option('pp_contact_email');
		$pp_contact_thankyou = __( 'Thank you! We will get back to you as soon as possible', THEMEDOMAIN );
		
		/*
		|
		| Begin sending mail
		|
		*/
		
		$from_name = $_POST['your_name'];
		$from_email = $_POST['email'];
		
		//Get contact subject
		if(!isset($_POST['subject']))
		{
			$contact_subject = __( 'Email from contact form', THEMEDOMAIN );
		}
		else
		{
			$contact_subject = $_POST['subject'];
		}
		
		$headers = "";
	   	$headers.= 'From: '.$from_name.' <'.$from_email.'>'.PHP_EOL;
	   	$headers.= 'Reply-To: '.$from_name.' <'.$from_email.'>'.PHP_EOL;
	   	$headers.= 'Return-Path: '.$from_name.' <'.$from_email.'>'.PHP_EOL;
		
		$message = __( 'Name', THEMEDOMAIN ).': '.$from_name.PHP_EOL;
		$message.= __( 'Email', THEMEDOMAIN ).': '.$from_email.PHP_EOL.PHP_EOL;
		$message.= __( 'Message', THEMEDOMAIN ).': '.$_POST['message'].PHP_EOL.PHP_EOL;
		
		if(isset($_POST['address']))
		{
			$message.= __( 'Address', THEMEDOMAIN ).': '.$_POST['address'].PHP_EOL;
		}
		
		if(isset($_POST['phone']))
		{
			$message.= __( 'Phone', THEMEDOMAIN ).': '.$_POST['phone'].PHP_EOL;
		}
		
		if(isset($_POST['mobile']))
		{
			$message.= __( 'Mobile', THEMEDOMAIN ).': '.$_POST['mobile'].PHP_EOL;
		}
		
		if(isset($_POST['company']))
		{
			$message.= __( 'Company', THEMEDOMAIN ).': '.$_POST['company'].PHP_EOL;
		}
		
		if(isset($_POST['country']))
		{
			$message.= __( 'Country', THEMEDOMAIN ).': '.$_POST['country'].PHP_EOL;
		}
		
		if(!empty($from_name) && !empty($from_email) && !empty($message))
		{
			wp_mail($contact_email, $contact_subject, $message, $headers);
			echo '<p>'.$pp_contact_thankyou.'</p>';
			
			die;
		}
		else
		{
			echo '<p>'.ERROR_MESSAGE.'</p>';
			
			die;
		}

	}
	else 
	{
		echo '<p>'.ERROR_MESSAGE.'</p>';
	}
	die();
}


/**
*	Setup contact form reservation function
**/
add_action('wp_ajax_pp_reservation_mailer', 'pp_reservation_mailer');
add_action('wp_ajax_nopriv_pp_reservation_mailer', 'pp_reservation_mailer');

function pp_reservation_mailer() {
	check_ajax_referer( 'tgajax-post-contact-nonce', 'tg_security' );
	
	//Error message when message can't send
	define('ERROR_MESSAGE', 'Oops! something went wrong, please try to submit later.');

	if (isset($_POST['your_name'])) {
	
		//Get your email address
		$contact_email = get_option('pp_reservation_email');
		$pp_contact_thankyou = __( 'Thank you! We will get back to you as soon as possible', THEMEDOMAIN );
		
		/*
		|
		| Begin sending mail
		|
		*/
		
		$order_no_people = $_POST['order_no_people'];
		$order_date = $_POST['order_date'];
		$order_time = $_POST['order_time'];
		
		$from_name = $_POST['your_name'];
		$from_email = $_POST['email'];
		$from_phone = $_POST['phone'];
		
		//Get contact subject
		$contact_subject = __( 'Email from reservation form', THEMEDOMAIN );
		
		$headers = "";
	   	$headers.= 'From: '.$from_name.' <'.$from_email.'>'.PHP_EOL;
	   	$headers.= 'Reply-To: '.$from_name.' <'.$from_email.'>'.PHP_EOL;
	   	$headers.= 'Return-Path: '.$from_name.' <'.$from_email.'>'.PHP_EOL;
		
		$no_people_title = $order_no_people;
		if($order_no_people==1)
		{
		    $no_people_title.=  ' '.__( 'person', THEMEDOMAIN );
		}
		elseif($order_no_people<20)
		{
		    $no_people_title.=  ' '.__( 'people', THEMEDOMAIN );
		}
		elseif($order_no_people==20)
		{
		    $no_people_title.=  '+ '.__( 'people', THEMEDOMAIN );
		}
		
		$message = __( 'Number of people', THEMEDOMAIN ).': '.$no_people_title.PHP_EOL;
		$message.= __( 'Date', THEMEDOMAIN ).': '.$order_date.PHP_EOL.PHP_EOL;
		$message.= __( 'Time', THEMEDOMAIN ).': '.$order_time.PHP_EOL.PHP_EOL;
		
		$message.= __( 'Special Requests', THEMEDOMAIN ).': '.$_POST['message'].PHP_EOL.PHP_EOL;
		
		$message.= __( 'Name', THEMEDOMAIN ).': '.$from_name.PHP_EOL;
		$message.= __( 'Phone', THEMEDOMAIN ).': '.$from_phone.PHP_EOL.PHP_EOL;
		$message.= __( 'Email', THEMEDOMAIN ).': '.$from_email.PHP_EOL.PHP_EOL;
		    
		
		if(!empty($from_name) && !empty($from_email) && !empty($message))
		{
			wp_mail($contact_email, $contact_subject, $message, $headers);
			echo '<p>'.$pp_contact_thankyou.'</p>';
			
			die;
		}
		else
		{
			echo '<p>'.ERROR_MESSAGE.'</p>';
			
			die;
		}

	}
	else 
	{
		echo '<p>'.ERROR_MESSAGE.'</p>';
	}
	die();
}


function pp_add_admin() {
 
global $themename, $shortname, $options, $pp_include_from_skin_arr;

if ( isset($_GET['page']) && $_GET['page'] == basename(__FILE__) ) {
 
	if ( isset($_REQUEST['action']) && 'save' == $_REQUEST['action'] ) {
 
		foreach ($options as $value) 
		{
			if($value['type'] != 'image' && isset($value['id']) && isset($_REQUEST[ $value['id'] ]))
			{
				update_option( $value['id'], $_REQUEST[ $value['id'] ] );
			}
		}
		
		foreach ($options as $value) {
		
			if( isset($value['id']) && isset( $_REQUEST[ $value['id'] ] )) 
			{ 

				if($value['id'] != SHORTNAME."_sidebar0" && $value['id'] != SHORTNAME."_ggfont0")
				{
					//if sortable type
					if(is_admin() && $value['type'] == 'sortable')
					{
						$sortable_array = serialize($_REQUEST[ $value['id'] ]);
						
						$sortable_data = $_REQUEST[ $value['id'].'_sort_data'];
						$sortable_data_arr = explode(',', $sortable_data);
						$new_sortable_data = array();
						
						foreach($sortable_data_arr as $key => $sortable_data_item)
						{
							$sortable_data_item_arr = explode('_', $sortable_data_item);
							
							if(isset($sortable_data_item_arr[0]))
							{
								$new_sortable_data[] = $sortable_data_item_arr[0];
							}
						}
						
						update_option( $value['id'], $sortable_array );
						update_option( $value['id'].'_sort_data', serialize($new_sortable_data) );
					}
					elseif(is_admin() && $value['type'] == 'font')
					{
						if(!empty($_REQUEST[ $value['id'] ]))
						{
							update_option( $value['id'], $_REQUEST[ $value['id'] ] );
							update_option( $value['id'].'_value', $_REQUEST[ $value['id'].'_value' ] );
						}
						else
						{
							delete_option( $value['id'] );
							delete_option( $value['id'].'_value' );
						}
					}
					elseif(is_admin())
					{
						if($value['type']=='image')
						{
							update_option( $value['id'], esc_url($_REQUEST[ $value['id'] ])  );
						}
						elseif($value['type']=='textarea')
						{
							update_option( $value['id'], esc_textarea($_REQUEST[ $value['id'] ])  );
						}
						elseif($value['type']=='iphone_checkboxes' OR $value['type']=='jslider')
						{
							update_option( $value['id'], intval($_REQUEST[ $value['id'] ])  );
						}
					
						update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
					}
				}
				elseif(is_admin() && isset($_REQUEST[ $value['id'] ]) && !empty($_REQUEST[ $value['id'] ]))
				{
					if($value['id'] == SHORTNAME."_sidebar0")
					{
						//get last sidebar serialize array
						$current_sidebar = get_option(SHORTNAME."_sidebar");
						$current_sidebar[ $_REQUEST[ $value['id'] ] ] = $_REQUEST[ $value['id'] ];
			
						update_option( SHORTNAME."_sidebar", $current_sidebar );
					}
					elseif($value['id'] == SHORTNAME."_ggfont0")
					{
						//get last ggfonts serialize array
						$current_ggfont = get_option(SHORTNAME."_ggfont");
						$current_ggfont[ $_REQUEST[ $value['id'] ] ] = $_REQUEST[ $value['id'] ];
			
						update_option( SHORTNAME."_ggfont", $current_ggfont );
					}
				}
			} 
			else 
			{ 
				if(is_admin() && isset($value['id']))
				{
					delete_option( $value['id'] );
				}
			} 
		}
		
		if(isset($_POST['pp_save_skin_flg']) && !empty($_POST['pp_save_skin_flg']) && $_GET["page"] == "functions.php")
		{
			global $wpdb;
			$ppskin_id = SKINSHORTNAME."_".time();
			
			$wpdb->query("SELECT * FROM `".$wpdb->prefix."options` WHERE `option_name` LIKE '%pp_%'");
			$pp_settings_obj = $wpdb->last_result;
			$serilize_settings_arr = array();
			
			$serilize_settings_arr['id'] = $ppskin_id;
			$serilize_settings_arr['name'] = $_POST['pp_save_skin_name'];
			foreach ($pp_settings_obj as $pp_setting)
			{
				if(in_array($pp_setting->option_name, $pp_include_from_skin_arr))
				{
					$serilize_settings_arr['settings'][$pp_setting->option_name] = $pp_setting->option_value;
				}
			}
			
			add_option($ppskin_id, $serilize_settings_arr);
			header("Location: admin.php?page=functions.php&saved=true#pp_panel_skins");
			exit;
		}

		header("Location: admin.php?page=functions.php&saved=true".$_REQUEST['current_tab']);
	}  
} 
 
add_menu_page('Theme Setting', 'Theme Setting', 'administrator', basename(__FILE__), 'pp_admin', get_admin_url().'/images/generic.png');
}

function pp_enqueue_admin_page_scripts() {

$file_dir=get_template_directory_uri();
wp_enqueue_style('thickbox');
wp_enqueue_style("functions", $file_dir."/functions/functions.css", false, THEMEVERSION, "all");
wp_enqueue_style("colorpicker_css", $file_dir."/functions/colorpicker/css/colorpicker.css", false, THEMEVERSION, "all");
wp_enqueue_style("fancybox", $file_dir."/js/fancybox/jquery.fancybox.admin.css", false, THEMEVERSION, "all");
wp_enqueue_style("icheck", $file_dir."/functions/skins/flat/green.css", false, THEMEVERSION, "all");
wp_enqueue_style("jquery-ui", $file_dir."/functions/jquery-ui/css/custom-theme/jquery-ui-1.8.24.custom.css", false, "1.0", "all");
wp_enqueue_style("jquery.timepicker", $file_dir."/functions/jquery.timepicker.css", false, "1.0", "all");

$pp_font = get_option('pp_font');
if(!empty($pp_font))
{
	wp_enqueue_style('google_fonts', "https://fonts.googleapis.com/css?family=".$pp_font."&subset=latin,cyrillic", false, "", "all");
}

wp_enqueue_script("jquery-ui-core");
wp_enqueue_script("jquery-ui-sortable");
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_enqueue_script("colorpicker_script", $file_dir."/functions/colorpicker/js/colorpicker.js", false, THEMEVERSION);
wp_enqueue_script("eye_script", $file_dir."/functions/colorpicker/js/eye.js", false, THEMEVERSION);
wp_enqueue_script("utils_script", $file_dir."/functions/colorpicker/js/utils.js", false, THEMEVERSION);
wp_enqueue_script("jquery.icheck.min", $file_dir."/functions/jquery.icheck.min.js", false, THEMEVERSION);
wp_enqueue_script("jslider_depend", $file_dir."/functions/jquery.dependClass.js", false, THEMEVERSION);
wp_enqueue_script("jslider", $file_dir."/functions/jquery.slider-min.js", false, THEMEVERSION);
wp_enqueue_script("fancybox", $file_dir."/js/fancybox/jquery.fancybox.admin.js", false);
wp_enqueue_script("hint", $file_dir."/js/hint.js", false, THEMEVERSION, true);
wp_enqueue_script("jquery-ui-datepicker");
wp_enqueue_script("jquery.timepicker", $file_dir."/functions/jquery.timepicker.js", false);
wp_enqueue_script("rm_script", $file_dir."/functions/rm_script.js", false, THEMEVERSION);

}

add_action('admin_enqueue_scripts',	'pp_enqueue_admin_page_scripts' );

function pp_enqueue_front_page_scripts() {

    //enqueue frontend css files
	$pp_advance_combine_css = get_option('pp_advance_combine_css');
	
	//If enable animation
	$pp_animation = get_option('pp_animation');
	    
	if(!empty($pp_advance_combine_css))
	{
	    if(!file_exists(get_template_directory_uri()."/cache/combined.css"))
	    {
	    	$cssmin = new CSSMin();
	    	
	    	$css_arr = array(
	    	    get_template_directory().'/js/fancybox/jquery.fancybox.css',
	    	    get_template_directory().'/css/jqueryui/custom.css',
	    	    get_template_directory().'/js/mediaelement/mediaelementplayer.css',
	    	    get_template_directory().'/js/flexslider/flexslider.css',
	    	    get_template_directory().'/css/tooltipster.css',
	    	    get_template_directory().'/css/parallax.min.css',
	    	    get_template_directory().'/css/screen.css',
	    	);
	    	
	    	if($pp_animation)
	    	{
		    	$css_arr[] = get_template_directory().'/css/animation.css';
	    	}
	    	
	    	$cssmin->addFiles($css_arr);
	    	
	    	// Set original CSS from all files
	    	$cssmin->setOriginalCSS();
	    	$cssmin->compressCSS();
	    	
	    	$css = $cssmin->printCompressedCSS();
	    	
	    	file_put_contents(get_template_directory()."/cache/combined.css", $css);
	    }
	    
	    wp_enqueue_style("combined_css", get_template_directory_uri()."/cache/combined.css", false, THEMEVERSION);
	}
	else
	{
		if($pp_animation)
	    {
	    	wp_enqueue_style("animation.css", get_template_directory_uri()."/css/animation.css", false, THEMEVERSION, "all");
	    }
	    wp_enqueue_style("jquery-ui-css", get_template_directory_uri()."/css/jqueryui/custom.css", false, THEMEVERSION);
	    wp_enqueue_style("fancybox", get_template_directory_uri()."/js/fancybox/jquery.fancybox.css", false, THEMEVERSION, "all");
	    wp_enqueue_style("flexslider", get_template_directory_uri()."/js/flexslider/flexslider.css", false, THEMEVERSION, "all");
	    wp_enqueue_style("mediaelement", get_template_directory_uri()."/js/mediaelement/mediaelementplayer.css", false, THEMEVERSION, "all");
	    wp_enqueue_style("tooltipster", get_template_directory_uri()."/css/tooltipster.css", false, THEMEVERSION, "all");
		wp_enqueue_style("parallax", get_template_directory_uri()."/css/parallax.min.css", false, THEMEVERSION, "all");
	    wp_enqueue_style("screen-css", get_template_directory_uri()."/css/screen.css", false, THEMEVERSION);
	}
	
	//Add Font Awesome Support
	wp_enqueue_style("fontawesome", get_template_directory_uri()."/css/font-awesome.min.css", false, THEMEVERSION, "all");
	wp_enqueue_style("custom_css", get_template_directory_uri()."/templates/custom-css.php", false, THEMEVERSION, "all");
	
	if(is_child_theme())
	{
	    wp_enqueue_style("childtheme-css", get_stylesheet_directory_uri()."/style.css", false, THEMEVERSION, "all");
	}
	
	//Get all Google Web font CSS
	global $gg_fonts;
	
	$gg_fonts_family = array();

	if(is_array($gg_fonts) && !empty($gg_fonts))
	{
		foreach($gg_fonts as $gg_font)
		{
			$gg_fonts_family[] = get_option($gg_font);
		}
	}
	
	$gg_fonts_family = array_unique($gg_fonts_family);

	foreach($gg_fonts_family as $key => $gg_fonts_family_value)
	{
		if(!empty($gg_fonts_family_value) && $gg_fonts_family_value != 'Helvetica' && $gg_fonts_family_value != 'Arial')
		{
			wp_enqueue_style('google_font'.$key, "https://fonts.googleapis.com/css?family=".$gg_fonts_family_value.":200,300,400,500,600,700,400italic&subset=latin,cyrillic-ext,greek-ext,cyrillic", false, "", "all");
		}
	}
	
	//Check if enable responsive layout
	$pp_enable_responsive = get_option('pp_enable_responsive');
	
	if(!empty($pp_enable_responsive))
	{
		if(!empty($pp_advance_combine_css))
		{
			wp_enqueue_style('responsive', get_template_directory_uri()."/templates/responsive-css.php", false, "", "all");
		}
		else
		{
	    	wp_enqueue_style('responsive', get_template_directory_uri()."/css/grid.css", false, "", "all");
	    }
	}
	
	//Enqueue javascripts
	wp_enqueue_script("jquery");
	
	capella_set_map_api();
	
	wp_enqueue_script("fancybox", get_template_directory_uri()."/js/fancybox/jquery.fancybox.js", false, THEMEVERSION, true);
	wp_enqueue_script("parallax", get_template_directory_uri()."/js/parallax.min.js", false, THEMEVERSION, true);
	
	$js_path = get_template_directory()."/js/";
	$js_arr = array(
		'jquery.easing.js',
	    'waypoints.min.js',
	    'jquery.isotope.js',
	    'jquery.masory.js',
	    'jquery.tooltipster.min.js',
	    'custom.js',
	);
	$js = "";

	$pp_advance_combine_js = get_option('pp_advance_combine_js');
	
	if(!empty($pp_advance_combine_js))
	{	
		if(!file_exists(get_template_directory()."/cache/combined.js"))
		{
			foreach($js_arr as $file) {
				if($file != 'jquery.js' && $file != 'jquery-ui.js')
				{
    				$js .= JSMin::minify(file_get_contents($js_path.$file));
    			}
			}
			
			file_put_contents(get_template_directory()."/cache/combined.js", $js);
		}

		wp_enqueue_script("combined_js", get_template_directory_uri()."/cache/combined.js", false, THEMEVERSION, true);
	}
	else
	{
		foreach($js_arr as $file) {
			if($file != 'jquery.js' && $file != 'jquery-ui.js')
			{
				wp_enqueue_script($file, get_template_directory_uri()."/js/".$file, false, THEMEVERSION, true);
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'pp_enqueue_front_page_scripts' );

function add_menu_icons_styles(){
?>
 
<style>
#adminmenu .menu-icon-events div.wp-menu-image:before {
  content: '\f145';
}
#adminmenu .menu-icon-menus div.wp-menu-image:before {
  content: '\f119';
}
#adminmenu .menu-icon-galleries div.wp-menu-image:before {
  content: '\f161';
}
#adminmenu .menu-icon-testimonials div.wp-menu-image:before {
  content: '\f155';
}
#adminmenu .menu-icon-team div.wp-menu-image:before {
  content: '\f307';
}
</style>
 
<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );

function pp_admin() {
 
global $themename, $shortname, $options;
$i=0;

$pp_font_family = get_option('pp_font_family');

if(function_exists( 'wp_enqueue_media' )){
    wp_enqueue_media();
}
?>

<style>
#pp_sample_text
{
	font-family: '<?php echo $pp_font_family; ?>';
}
</style>
	
	<div id="pp_loading"><span><?php _e( 'Updating...', THEMEDOMAIN ); ?></span></div>
	<div id="pp_success"><span><?php _e( 'Successfully<br/>Update', THEMEDOMAIN ); ?></span></div>
	
	<?php
		if(isset($_GET['saved']) == 'true')
		{
	?>
		<script>
			jQuery('#pp_success').show();
	            	
	        setTimeout(function() {
              jQuery('#pp_success').fadeOut();
            }, 2000);
		</script>
	<?php
		}
	?>
	
	<form id="pp_form" method="post" enctype="multipart/form-data">
	<div class="pp_wrap rm_wrap">
	
	<div class="header_wrap">
		<div style="float:left">
		<h2><?php _e( 'Theme Setting', THEMEDOMAIN ); ?></h2>(v<?php echo THEMEVERSION; ?>)
		For future updates follow me <a href="http://themeforest.net/user/ThemeGoods">@themeforest</a> or <a href="http://twitter.com/ipeerapong">@twitter</a>
		</div>
		<div style="float:right;margin:32px 0 0 0">
			<!-- input id="save_ppskin" name="save_ppskin" class="button secondary_button" type="submit" value="Save as Skin" / -->
			<input id="save_ppsettings" name="save_ppsettings" class="button button-primary button-large" type="submit" value="<?php _e( 'Save All Changes', THEMEDOMAIN ); ?>" />
			<br/><br/>
			<input type="hidden" name="action" value="save" />
			<input type="hidden" name="current_tab" id="current_tab" value="#pp_panel_general" />
			<input type="hidden" name="pp_save_skin_flg" id="pp_save_skin_flg" value="" />
			<input type="hidden" name="pp_save_skin_name" id="pp_save_skin_name" value="" />
		</div>
		<input type="hidden" name="pp_admin_url" id="pp_admin_url" value="<?php echo get_template_directory_uri(); ?>"/>
		<br style="clear:both"/><br/>
		
		<?php
$cache_dir = get_template_directory().'/cache';
$data_dir = THEMEUPLOAD;

if(!is_writable($cache_dir))
{
?>

	<div id="message" class="error fade">
	<p style="line-height:1.5em"><strong>
		The path <?php echo $cache_dir; ?> is not writable, please login with your FTP account and make it writable (chmod 777) otherwise CSS and javascript compression feature won't work.
	</p></strong>
	</div>

<?php
}
?>
		
		<?php
			if ( isset($_REQUEST['activate']) &&  $_REQUEST['activate'] ) 
			{
		?>		
			
			<div id="message" class="updated fade">
				<p>
					<strong>Do you want to import demo theme settings?</strong><br/><br/>
					<strong>*NOTE:</strong> Default them setting is not sample content. It imports only theme admin panel settings including colors, font etc. You still have to add your own contents ex. pages, post, menu, events etc.<br/><br/>
					<input id="pp_import_default_button" name="pp_import_default_button" type="submit" value="Import Settings" class="upload_btn button-primary"/>
					<?php
						//Get admin URL
						$admin_url = admin_url("themes.php?page=functions.php");
					?>
					<a href="<?php echo $admin_url; ?>" id="pp_import_dismiss_button" name="pp_import_dismiss_button" class="button"/>Cancel</a>
					<input type="hidden" id="pp_import_default" name="pp_import_default" value=""/>
				</p>
			</p>
			</div>
			<br/>
			
		<?php
			}
		?>		
	</div>
	
	<div class="pp_wrap">
	<div id="pp_panel">
	<?php 
		foreach ($options as $value) {
			/*print '<pre>';
			print_r($value);
			print '</pre>';*/
			
			$active = '';
			
			if($value['type'] == 'section')
			{
				if($value['name'] == 'General')
				{
					$active = 'nav-tab-active';
				}
				echo '<a id="pp_panel_'.strtolower($value['name']).'_a" href="#pp_panel_'.strtolower($value['name']).'" class="nav-tab '.$active.'"><img src="'.get_template_directory_uri().'/functions/images/icon/'.$value['icon'].'" class="ver_mid"/>'.str_replace('-', ' ', $value['name']).'</a>';
			}
		}
	?>
	</h2>
	</div>

	<div class="rm_opts">
	
<?php 

// Get Google font list from cache
$pp_font_arr = array();

$font_cache_path = get_template_directory().'/cache/gg_fonts.cache';
$file = file_get_contents($font_cache_path, true);
$pp_font_arr = unserialize($file);

//Get installed Google font (if has)
$current_ggfont = get_option('pp_ggfont');

//Get 2 default font
$pp_font_arr[] = array(
	'font-family' => 'font-family: "Helvetica"',
	'font-name' => 'Helvetica',
	'css-name' => urlencode('Helvetica'),
);

$pp_font_arr[] = array(
    'font-family' => 'font-family: "Arial"',
    'font-name' => 'Arial',
    'css-name' => urlencode('Arial'),
);

if(!empty($current_ggfont))
{
	foreach($current_ggfont as $ggfont)
	{
		$pp_font_arr[] = array(
			'font-family' => 'font-family: \''.$ggfont.'\'',
			'font-name' => $ggfont,
			'css-name' => urlencode($ggfont),
		);
	}
}

//Sort by font name
function cmp($a, $b)
{
    return strcmp($a["font-name"], $b["font-name"]);
}
usort($pp_font_arr, "cmp");

$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

foreach ($options as $value) {
switch ( $value['type'] ) {
 
case "open":
?> <?php break;
 
case "close":
?>
	
	</div>
	</div>


	<?php break;
 
case "title":
?>
	<br />


<?php break;
 
case 'text':
	
	//if sidebar input then not show default value
	if($value['id'] != SHORTNAME."_sidebar0" && $value['id'] != SHORTNAME."_ggfont0")
	{
		$default_val = get_option( $value['id'] );
	}
	else
	{
		$default_val = '';	
	}
?>

	<div class="rm_input rm_text"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/>
	<input name="<?php echo $value['id']; ?>"
		id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>"
		value="<?php if ($default_val != "") { echo get_option( $value['id']) ; } else { echo $value['std']; } ?>"
		<?php if(!empty($value['size'])) { echo 'style="width:'.$value['size'].'"'; } ?> />
		<small><?php echo $value['desc']; ?></small>
	<div class="clearfix"></div>
	
	<?php
	if($value['id'] == SHORTNAME."_sidebar0")
	{
		$current_sidebar = get_option(SHORTNAME."_sidebar");
		
		if(!empty($current_sidebar))
		{
	?>
		<br class="clear"/><br/>
	 	<div class="pp_sortable_wrapper">
		<ul id="current_sidebar" class="rm_list">

	<?php
		foreach($current_sidebar as $sidebar)
		{
	?> 
			
			<li id="<?php echo $sidebar; ?>"><div class="title"><?php echo $sidebar; ?></div><a href="<?php echo $url; ?>" class="sidebar_del" rel="<?php echo $sidebar; ?>">Delete</a><br style="clear:both"/></li>
	
	<?php
		}
	?>
	
		</ul>
		</div>
	
	<?php
		}
	}
	elseif($value['id'] == SHORTNAME."_ggfont0")
	{
	?>
		<?php _e( 'Below are fonts that already installed.', THEMEDOMAIN ); ?><br/>
		<select name="<?php echo SHORTNAME; ?>_sample_ggfont" id="<?php echo SHORTNAME; ?>_sample_ggfont">
		<?php 
			foreach ($pp_font_arr as $key => $option) { ?>
		<option
		<?php if (get_option( $value['id'] ) == $option['css-name']) { echo 'selected="selected"'; } ?>
			value="<?php echo $option['css-name']; ?>" data-family="<?php echo $option['font-name']; ?>"><?php echo $option['font-name']; ?></option>
		<?php } ?>
		</select> 
	<?php
		$current_ggfont = get_option(SHORTNAME."_ggfont");
		
		if(!empty($current_ggfont))
		{
	?>
		<br class="clear"/><br/>
	 	<div class="pp_sortable_wrapper">
		<ul id="current_ggfont" class="rm_list">

	<?php
	
		foreach($current_ggfont as $ggfont)
		{
	?> 
			
			<li id="<?php echo $ggfont; ?>"><div class="title"><?php echo $ggfont; ?></div><a href="<?php echo $url; ?>" class="ggfont_del" rel="<?php echo $ggfont; ?>">Delete</a><br style="clear:both"/></li>
	
	<?php
		}
	?>
	
		</ul>
		</div>
	
	<?php
		}
	}
	?>

	</div>
	<?php
break;

case 'password':
?>

	<div class="rm_input rm_text"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/>
	<input name="<?php echo $value['id']; ?>"
		id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>"
		value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?>"
		<?php if(!empty($value['size'])) { echo 'style="width:'.$value['size'].'"'; } ?> />
	<small><?php echo $value['desc']; ?></small>
	<div class="clearfix"></div>

	</div>
	<?php
break;

break;

case 'image':
case 'music':
?>

	<div class="rm_input rm_text"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/>
	<input id="<?php echo $value['id']; ?>" type="text" name="<?php echo $value['id']; ?>" value="<?php echo get_option($value['id']); ?>" style="width:200px" class="upload_text" readonly />
	<input id="<?php echo $value['id']; ?>_button" name="<?php echo $value['id']; ?>_button" type="button" value="Upload" class="upload_btn button" rel="<?php echo $value['id']; ?>" style="margin:7px 0 0 5px" />
	<small><?php echo $value['desc']; ?></small>
	<div class="clearfix"></div>
	
	<script>
	jQuery(document).ready(function() {
		jQuery('#<?php echo $value['id']; ?>_button').click(function() {
         	var send_attachment_bkp = wp.media.editor.send.attachment;
		    wp.media.editor.send.attachment = function(props, attachment) {
		    	formfield = jQuery('#<?php echo $value['id']; ?>').attr('name');
	         	jQuery('#'+formfield).attr('value', attachment.url);
	         	jQuery('#pp_form').submit();
		
		        wp.media.editor.send.attachment = send_attachment_bkp;
		    }
		
		    wp.media.editor.open();
        });
    });
	</script>
	
	<?php 
		$current_value = get_option( $value['id'] );
		
		if(!is_bool($current_value) && !empty($current_value))
		{
			$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		
			if($value['type']=='image')
			{
	?>
	
		<div id="<?php echo $value['id']; ?>_wrapper" style="width:380px;font-size:11px;"><br/>
			<img src="<?php echo get_option($value['id']); ?>" style="max-width:500px"/><br/><br/>
			<a href="<?php echo $url; ?>" class="image_del button" rel="<?php echo $value['id']; ?>">Delete</a>
		</div>
		<?php
			}
			else
			{
		?>
		<div id="<?php echo $value['id']; ?>_wrapper" style="width:380px;font-size:11px;">
			<br/><a href="<?php echo get_option( $value['id'] ); ?>">
			Listen current music</a>&nbsp;<a href="<?php echo $url; ?>" class="image_del button" rel="<?php echo $value['id']; ?>">Delete</a>
		</div>
	<?php
			}
		}
	?>

	</div>
	<?php
break;

case 'jslider':
?>

	<div class="rm_input rm_text"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/>
	<div style="float:left;width:290px;margin-top:10px">
	<input name="<?php echo $value['id']; ?>"
		id="<?php echo $value['id']; ?>" type="text" class="jslider"
		value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?>"
		<?php if(!empty($value['size'])) { echo 'style="width:'.$value['size'].'"'; } ?> />
	</div>
	<small><?php echo $value['desc']; ?></small>
	<div class="clearfix"></div>
	
	<script>jQuery("#<?php echo $value['id']; ?>").slider({ from: <?php echo $value['from']; ?>, to: <?php echo $value['to']; ?>, step: <?php echo $value['step']; ?>, smooth: true, skin: "round_plastic" });</script>

	</div>
	<?php
break;

case 'colorpicker':
?>
	<div class="rm_input rm_text"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/>
	<input name="<?php echo $value['id']; ?>"
		id="<?php echo $value['id']; ?>" type="text" 
		value="<?php if ( get_option( $value['id'] ) != "" ) { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?>"
		<?php if(!empty($value['size'])) { echo 'style="width:'.$value['size'].'"'; } ?>  class="color_picker" readonly/>
	<div id="<?php echo $value['id']; ?>_bg" class="colorpicker_bg" onclick="jQuery('#<?php echo $value['id']; ?>').click()" style="background:<?php if (get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?> url(<?php echo get_template_directory_uri(); ?>/functions/images/trigger.png) center no-repeat;">&nbsp;</div>
		<small><?php echo $value['desc']; ?></small>
	<div class="clearfix"></div>
	
	</div>
	
<?php
break;
 
case 'textarea':
?>

	<div class="rm_input rm_textarea"><label
		for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/>
	<textarea name="<?php echo $value['id']; ?>"
		type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id']) ); } else { echo $value['std']; } ?></textarea>
	<small><?php echo $value['desc']; ?></small>
	<div class="clearfix"></div>

	</div>

	<?php
break;
 
case 'select':
?>

	<div class="rm_input rm_select"><label
		for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/>

	<select name="<?php echo $value['id']; ?>"
		id="<?php echo $value['id']; ?>">
		<?php foreach ($value['options'] as $key => $option) { ?>
		<option
		<?php if (get_option( $value['id'] ) == $key) { echo 'selected="selected"'; } ?>
			value="<?php echo $key; ?>"><?php echo $option; ?></option>
		<?php } ?>
	</select> <small><?php echo $value['desc']; ?></small>
	<div class="clearfix"></div>
	</div>
	<?php
break;

case 'font':
?>

	<div class="rm_input rm_font"><label
		for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/>

	<div id="<?php echo $value['id']; ?>_wrapper" style="float:left;font-size:11px;">
	<select class="pp_font" data-sample="<?php echo $value['id']; ?>_sample" data-value="<?php echo $value['id']; ?>_value" name="<?php echo $value['id']; ?>"
		id="<?php echo $value['id']; ?>">
		<option value="" data-family="">---- Theme Default Font ----</option>
		<?php 
			foreach ($pp_font_arr as $key => $option) { ?>
		<option
		<?php if (get_option( $value['id'] ) == $option['css-name']) { echo 'selected="selected"'; } ?>
			value="<?php echo $option['css-name']; ?>" data-family="<?php echo $option['font-name']; ?>"><?php echo $option['font-name']; ?></option>
		<?php } ?>
	</select> 
	<input type="hidden" id="<?php echo $value['id']; ?>_value" name="<?php echo $value['id']; ?>_value" value="<?php echo get_option( $value['id'].'_value' ); ?>"/>
	<br/><br/><div id="<?php echo $value['id']; ?>_sample" class="pp_sample_text">Sample Text</div>
	</div>
	<small><?php echo $value['desc']; ?></small>
	<div class="clearfix"></div>
	</div>
	<?php
break;
 
case 'radio':
?>

	<div class="rm_input rm_select"><label
		for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/><br/>

	<div style="margin-top:5px;float:left;<?php if(!empty($value['desc'])) { ?>width:300px<?php } else { ?>width:500px<?php } ?>">
	<?php foreach ($value['options'] as $key => $option) { ?>
	<div style="float:left;<?php if(!empty($value['desc'])) { ?>margin:0 20px 20px 0<?php } ?>">
		<input style="float:left;" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" type="radio"
		<?php if (get_option( $value['id'] ) == $key) { echo 'checked="checked"'; } ?>
			value="<?php echo $key; ?>"/><?php echo $option; ?>
	</div>
	<?php } ?>
	</div>
	
	<?php if(!empty($value['desc'])) { ?>
		<small><?php echo $value['desc']; ?></small>
	<?php } ?>
	<div class="clearfix"></div>
	</div>
	<?php
break;

case 'skin':
	global $wpdb;
	$pp_skins_obj = array();
	
	$wpdb->query("SELECT * FROM `".$wpdb->prefix."options` WHERE `option_name` LIKE '%".SKINSHORTNAME."_%'");
	$pp_skins_obj = $wpdb->last_result;
	//pp_debug($pp_skins_obj);
	
	if ($_SERVER["SERVER_PORT"] != "80") {
	  	$api_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].":".$_SERVER["SERVER_PORT"].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].":".$_SERVER["SERVER_PORT"].$_SERVER['REQUEST_URI'];
	} else {
	  	$api_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	}
?>

	<div class="rm_input" style="margin-top:10px">
	<label for="pp_theme_layout">
		<h2>Skins Manager</h2>
	</label>
	<?php echo $value['desc']; ?>
	<br style="clear:both"/><br/>

	<ul class="pp_skin_mgmt"> 
	 <?php 
		foreach ($pp_skins_obj as $key => $pp_skin) { 
			//Get skin name	
			$pp_skin_arr = unserialize($pp_skin->option_value);
			//pp_debug(unserialize($pp_skin_arr));
	?>
	 		<li class="ui-state-default">
	 			<div class="title"><?php echo $pp_skin_arr['name']; ?></div>
	 			<a data-rel="<?php echo $pp_skin_arr['id']; ?>" href="<?php echo $api_url; ?>" class="skin_remove remove">x</a>
	 			<a data-rel="<?php echo $pp_skin_arr['id']; ?>" href="<?php echo $api_url; ?>" class="skin_activate button">Activate</a>
	 			<br style="clear:both"/>
	 		</li> 	
	 <?php
	 	}
	 ?>
	 </ul>
	
	<div class="clearfix"></div>
	</div>
	<?php
break;

case 'sortable':
?>

	<div class="rm_input rm_select"><label
		for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/>

	<div style="float:left;width:100%;">
	<?php 
	$sortable_array = array();
	if(get_option( $value['id'] ) != 1)
	{
		$sortable_array = unserialize(get_option( $value['id'] ));
	}
	
	$current = 1;
	
	if(!empty($value['options']))
	{
	?>
	<select name="<?php echo $value['id']; ?>"
		id="<?php echo $value['id']; ?>" class="pp_sortable_select">
	<?php
	foreach ($value['options'] as $key => $option) { 
		if($key > 0)
		{
	?>
	<option value="<?php echo $key; ?>" data-rel="<?php echo $value['id']; ?>_sort" title="<?php echo html_entity_decode($option); ?>"><?php echo html_entity_decode($option); ?></option>
	<?php }
	
			if($current>1 && ($current-1)%3 == 0)
			{
	?>
	
			<br style="clear:both"/>
	
	<?php		
			}
			
			$current++;
		}
	?>
	</select>
	<a class="button pp_sortable_button" data-rel="<?php echo $value['id']; ?>" class="button" style="margin-top:10px;display:inline-block">Add</a>
	<?php
	}
	?>
	 
	 <br style="clear:both"/><br/>
	 
	 <div class="pp_sortable_wrapper">
	 <ul id="<?php echo $value['id']; ?>_sort" class="pp_sortable" rel="<?php echo $value['id']; ?>_sort_data"> 
	 <?php
	 	$sortable_data_array = unserialize(get_option( $value['id'].'_sort_data' ));

	 	if(!empty($sortable_data_array))
	 	{
	 		foreach($sortable_data_array as $key => $sortable_data_item)
	 		{
		 		if(!empty($sortable_data_item))
		 		{
	 		
	 ?>
	 		<li id="<?php echo $sortable_data_item; ?>_sort" class="ui-state-default"><div class="title"><?php echo $value['options'][$sortable_data_item]; ?></div><a data-rel="<?php echo $value['id']; ?>_sort" href="javascript:;" class="remove">x</a><br style="clear:both"/></li> 	
	 <?php
	 			}
	 		}
	 	}
	 ?>
	 </ul>
	 
	 </div>
	 
	</div>
	
	<input type="hidden" id="<?php echo $value['id']; ?>_sort_data" name="<?php echo $value['id']; ?>_sort_data" value="" style="width:100%"/>
	<br style="clear:both"/><br/>
	
	<div class="clearfix"></div>
	</div>
	<?php
break;
 
case "checkbox":
?>

	<div class="rm_input rm_checkbox"><label
		for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/>

	<?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
	<input type="checkbox" name="<?php echo $value['id']; ?>"
		id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />


	<small><?php echo $value['desc']; ?></small>
	<div class="clearfix"></div>
	</div>
<?php break; 

case "iphone_checkboxes":
?>

	<div class="rm_input rm_checkbox"><label
		for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>

	<?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
	<input type="checkbox" class="iphone_checkboxes" name="<?php echo $value['id']; ?>"
		id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />

	<small><?php echo $value['desc']; ?></small>
	<div class="clearfix"></div>
	</div>

<?php break; 

case "html":
?>

	<div class="rm_input rm_checkbox"><label
		for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/>

	<?php echo $value['html']; ?>

	<small><?php echo $value['desc']; ?></small>
	<div class="clearfix"></div>
	</div>

<?php break; 
	
case "section":

$i++;

?>

	<div id="pp_panel_<?php echo strtolower($value['name']); ?>" class="rm_section">
	<div class="rm_title">
	<h3><img
		src="<?php echo get_template_directory_uri(); ?>/functions/images/trans.png"
		class="inactive" alt=""><?php echo $value['name']; ?></h3>
	<span class="submit"><input class="button-primary" name="save<?php echo $i; ?>" type="submit"
		value="Save changes" /> </span>
	<div class="clearfix"></div>
	</div>
	<div class="rm_options"><?php break;
 
}
}
?>
 	
 	<div class="clearfix"></div>
 	</form>
 	</div>
</div>


	<?php
}

add_action('admin_menu', 'pp_add_admin');

//Setup content builder
include (get_template_directory() . "/modules/content_builder.php");

// Setup shortcode generator
include (get_template_directory() . "/modules/shortcode_generator.php");

// Setup Twitter API
include (get_template_directory() . "/modules/twitteroauth.php");


function pp_tag_cloud_filter($args = array()) {
   $args['smallest'] = 13;
   $args['largest'] = 13;
   $args['unit'] = 'px';
   return $args;
}

add_filter('widget_tag_cloud_args', 'pp_tag_cloud_filter', 90);

//Control post excerpt length
function custom_excerpt_length( $length ) {
	return 15;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 200 );

/** Plugin Name: (#73190) Alter adjacent post link sort order */
/*function wpse73190_adjacent_post_sort( $orderby )
{
    return "ORDER BY p.menu_order DESC LIMIT 1";
}
add_filter( 'get_previous_post_sort', 'wpse73190_adjacent_post_sort' );
add_filter( 'get_next_post_sort', 'wpse73190_adjacent_post_sort' );*/

global $shop_product_columns;
$shop_product_columns = 3;
$shop_page_id = get_option( 'woocommerce_shop_page_id' );
$page_sidebar = get_post_meta($shop_page_id, 'page_sidebar', true);

if(!empty($page_sidebar))
{
	$shop_product_columns = 3;
}

if(isset($_GET['show_sidebar']))
{
    $shop_product_columns = 3;
}
add_filter('loop_shop_columns', 'wc_product_columns_frontend');

function wc_product_columns_frontend() {
    global $woocommerce;
    global $shop_product_columns;

    // Default Value also used for categories and sub_categories
    $columns = $shop_product_columns;

    // Product List
    if ( is_product_category() ) :
        $columns = $shop_product_columns;
    endif;

    //Related Products
    if ( is_product() ) :
        $columns = $shop_product_columns;
    endif;

    //Cross Sells
    if ( is_checkout() ) :
        $columns = $shop_product_columns;
    endif;

	return $columns;
}

// Redefine woocommerce_output_related_products()
function woocommerce_output_related_products() {
	global $shop_product_columns;
	woocommerce_related_products($shop_product_columns,$shop_product_columns); // Display 4 products in rows of 2
}

add_action('woocommerce_after_shop_loop_item', 'get_star_rating' );
function get_star_rating()
{
    global $woocommerce, $product;
    $average = $product->get_average_rating();

    echo '<div class="star-rating"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'woocommerce' ).'</span></div>';
}

//Make widget support shortcode
add_filter('widget_text', 'do_shortcode');

add_filter( 'posts_where', 'wpse18703_posts_where', 10, 2 );
function wpse18703_posts_where( $where, $wp_query )
{
    global $wpdb;
    if ( $wpse18703_title = $wp_query->get( 'wpse18703_title' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $wpse18703_title ) ) . '%\'';
    }
    return $where;
}

if (isset($_GET['activated']) && $_GET['activated']){
	global $wpdb;
	
	// Run default settings
	include_once(get_template_directory() . "/default_settings.php");
	
    wp_redirect(admin_url("themes.php?page=functions.php&activate=true"));
}
?>