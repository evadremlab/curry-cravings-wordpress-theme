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

//important to apply dynamic header and footer style
global $pp_homepage_style;
$pp_homepage_style = 'fullscreen';

get_header(); 

//Run flow gallery data
wp_enqueue_script("jquery.ppflip", get_template_directory_uri()."/js/jquery.ppflip.js", false, THEMEVERSION, true);
wp_enqueue_script("jquery.touchwipe.1.1.1", get_template_directory_uri()."/js/jquery.touchwipe.1.1.1.js", false, THEMEVERSION, "all");
wp_enqueue_script("script-flow-gallery", get_template_directory_uri()."/templates/script-flow-gallery.php?gallery_id=".$current_page_id, false, THEMEVERSION, true);
?>

<br class="clear"/>

<div id="imageFlow">
	<div class="text">
		<div class="title"></div>
		<div class="legend"></div>
	</div>
</div>

<a id="imgflow-prevslide" class="load-item"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow_back.png" alt=""/></a>
<a id="imgflow-nextslide" class="load-item"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow_forward.png" alt=""/></a>

<input type="hidden" id="pp_image_path" name="pp_image_path" value="<?php echo get_template_directory_uri(); ?>/images/"/>

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