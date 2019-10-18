<?php
/**
 * The main template file for display page.
 *
 * @package WordPress
*/

/**
*	Get current page id
**/
$current_page_id = get_option( 'woocommerce_shop_page_id' );

get_header();

$page_sidebar = '';
if(!is_single())
{
	$page_sidebar = get_post_meta($current_page_id, 'page_sidebar', true);
	
	if(isset($_GET['show_sidebar']))
	{
		$page_sidebar = 'Shop Sidebar';
	}
}
?>

<?php
if(!is_single())
{
    //Get Page RevSlider
    $page_revslider = get_post_meta($current_page_id, 'page_revslider', true);
    $page_menu_transparent = get_post_meta($current_page_id, 'page_menu_transparent', true);
    
    if(!empty($page_revslider) && $page_revslider != -1)
    {
    	echo '<div class="page_slider ';
    	if(!empty($page_menu_transparent))
    	{
	    	echo 'menu_transparent';
    	}
    	echo '">'.do_shortcode('[rev_slider '.$page_revslider.']').'</div>';
    }
?>

<?php
//Get page header display setting
$page_hide_header = get_post_meta($current_page_id, 'page_hide_header', true);

if($page_revslider != -1 && !empty($page_menu_transparent))
{
	$page_hide_header = 1;
}

if(is_single())
{
	$page_hide_header = 1;
}

if(empty($page_hide_header) && $page_revslider == -1)
{
	$pp_page_bg = '';
	//Get page featured image
	if(has_post_thumbnail($current_page_id, 'full'))
    {
        $image_id = get_post_thumbnail_id($current_page_id); 
        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
        $pp_page_bg = $image_thumb[0];
    }
    
    $background_image = $image_thumb[0];
	$background_image_width = $image_thumb[1];
	$background_image_height = $image_thumb[2];
?>
<div id="page_caption" <?php if(!empty($pp_page_bg)) { ?>class="hasbg parallax <?php if(empty($page_menu_transparent)) { ?>notransparent<?php } ?>" data-image="<?php echo $background_image; ?>" data-width="<?php echo $background_image_width; ?>" data-height="<?php echo $background_image_height; ?>"<?php } ?>>
	<div class="page_title_wrapper">
		<h1><?php _e( 'Delivery', THEMEDOMAIN ); ?></h1>
		<?php echo dimox_breadcrumbs(); ?>
	</div>
	<?php if(!empty($pp_page_bg)) { ?>
		<div class="parallax_overlay_header"></div>
	<?php } ?>
</div>
<br class="clear"/>
<?php
}
else
{
?>
<br class="clear"/>
<?php
}

}
else
{
?>
<br class="clear"/>
<div id="page_caption" class="empty"></div>
<?php
} //if single page
?>

<!-- Begin content -->
<div id="page_content_wrapper" <?php if(!empty($pp_page_bg)) { ?>class="hasbg"<?php } ?>>
    <div class="inner ">
    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    		<div class="sidebar_content <?php if(empty($page_sidebar)) { ?>full_width<?php } ?>">
				
				<?php woocommerce_content();  ?>
				
    		</div>
    		<?php if(!empty($page_sidebar)) { ?>
    		<div class="sidebar_wrapper">
	            <div class="sidebar">
	            
	            	<div class="content">
	            
	            		<ul class="sidebar_widget">
	            		<?php dynamic_sidebar($page_sidebar); ?>
	            		</ul>
	            	
	            	</div>
	        
	            </div>
            <br class="clear"/>
        
            <div class="sidebar_bottom"></div>
			</div>
    		<?php } ?>
    	</div>
    	<!-- End main content -->
    </div>
</div>
<br class="clear"/><br/><br/>
<!-- End content -->
<?php get_footer(); ?>