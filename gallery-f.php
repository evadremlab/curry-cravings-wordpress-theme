<?php
/**
 * The main template file for display portfolio page.
 *
 * @package WordPress
 */

/**
*	Get Current page object
**/
$page = get_page($post->ID);
$current_page_id = '';

if(isset($page->ID))
{
    $current_page_id = $page->ID;
}

//Check if gallery template
global $page_gallery_id;
if(!empty($page_gallery_id))
{
	$current_page_id = $page_gallery_id;
}

//Check if password protected
$gallery_password = get_post_meta($current_page_id, 'gallery_password', true);
if(!empty($gallery_password))
{
	session_start();
	
	if(!isset($_SESSION['gallery_page_'.$current_page_id]) OR empty($_SESSION['gallery_page_'.$current_page_id]))
	{
		get_template_part("/templates/template-password");
		exit;
	}
}

//important to apply dynamic header & footer style
global $pp_homepage_style;
$pp_homepage_style = 'fullscreen';

get_header();

//Run gallery script data
wp_enqueue_style("supersized", get_template_directory_uri()."/css/supersized.css", false, THEMEVERSION, "all");
wp_enqueue_script("supersized.3.1.3", get_template_directory_uri()."/js/supersized.3.1.3.js", false, THEMEVERSION, "all");
wp_enqueue_script("supersized.shutter", get_template_directory_uri()."/js/supersized.shutter.js", false, THEMEVERSION, "all");
wp_enqueue_script("jquery.touchwipe.1.1.1", get_template_directory_uri()."/js/jquery.touchwipe.1.1.1.js", false, THEMEVERSION, "all");
wp_enqueue_script("script-supersized-gallery", get_template_directory_uri()."/templates/script-supersized-gallery.php?gallery_id=".$current_page_id, false, THEMEVERSION, true);
?>

<div id="thumb-tray" class="load-item">
    <a id="prevslide" class="load-item"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow_back.png" alt=""/></a>
    <a id="nextslide" class="load-item"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow_forward.png" alt=""/></a>
</div>

<?php
    $pp_full_enable_slideshow_title = get_option('pp_full_enable_slideshow_title');
    if(!empty($pp_full_enable_slideshow_title))
    {
?>
    <!--Slide captions displayed here--> 
    <div id="slidecaption"></div>
<?php
    }
?>

<br class="clear"/>
</div>

<?php
$gallery_audio = get_post_meta($current_page_id, 'gallery_audio', true);

if(!empty($gallery_audio))
{
?>
<div class="gallery_audio">
	<?php echo do_shortcode('[audio width="30" height="30" src="'.$gallery_audio.'"]'); ?>
</div>
<?php
}
?>

<?php	
	get_footer();
?>