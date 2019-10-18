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

//Get gallery images
$all_photo_arr = get_post_meta($current_page_id, 'wpsimplegallery_gallery', true);

//Get global gallery sorting
$all_photo_arr = pp_resort_gallery_img($all_photo_arr);

get_header(); 
?>

<br class="clear"/>

<?php
	$pp_page_bg = '';
	$image_thumb = array();
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
		<h1><?php the_title(); ?></h1>
		<?php 
			$pp_breadcrumbs_display = get_option('pp_breadcrumbs_display');
			
			if(!empty($pp_breadcrumbs_display))
			{
				echo dimox_breadcrumbs(); 
			}
		?>
	</div>
	<?php if(!empty($pp_page_bg)) { ?>
		<div class="parallax_overlay_header"></div>
	<?php } ?>
</div>

<?php
	global $global_pp_topbar;
?>
<div id="page_content_wrapper" class="<?php if(!empty($pp_page_bg)) { ?>hasbg<?php } ?> <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>withtopbar<?php } ?>">
    
    <div class="inner">

    	<div class="inner_wrapper">
	    
	    <?php 
    		//Get gallery content
    		$gallery_post_content = get_post_field('post_content', $current_page_id);
    		
    		if(!empty($gallery_post_content))
    		{
	    		echo $gallery_post_content;
    		}
    	?>
    	
    	<div id="page_main_content" class="sidebar_content full_width nopadding">
    	
    	<div id="portfolio_filter_wrapper" class="four_cols gallery section content clearfix">
    	<?php
	        $key = 0;
	        foreach($all_photo_arr as $photo_id)
	        {
	        	$small_image_url = '';
	        	$hyperlink_url = get_permalink($photo_id);
	        	
	        	if(!empty($photo_id))
	        	{
	        		$image_url = wp_get_attachment_image_src($photo_id, 'original', true);
	        	    $small_image_url = wp_get_attachment_image_src($photo_id, 'gallery_2', true);
	        	}
	        	
	        	$last_class = '';
	        	if(($key+1)%4==0)
	        	{
	        		$last_class = 'last';
	        	}
	        	
	        	$current_image_arr = wp_get_attachment_image_src($photo_id, 'gallery_2');
	        	
	        	//Get image meta data
	        	$image_title = get_the_title($photo_id);
	        	$image_caption = get_post_field('post_excerpt', $photo_id);
	        	$image_desc = get_post_field('post_content', $photo_id);
	    ?>
    	
    	<div class="element">
	    	
		    <div class="one_fourth gallery4 filterable gallery_type animated<?php echo $key+1; ?> static columns" data-id="post-<?php echo $key+1; ?>">
    		<?php 
    			if(!empty($small_image_url))
    			{
    				$pp_lightbox_enable_title = get_option('pp_lightbox_enable_title');
				    $pp_lightbox_enable_comment_share = get_option('pp_lightbox_enable_comment_share');
    				$pp_social_sharing = get_option('pp_social_sharing');
    		?>		
    				<a <?php if(!empty($pp_lightbox_enable_title)) { ?>data-title="<strong><?php echo $image_title; ?></strong> <?php if(!empty($image_desc)) { ?><?php echo htmlentities($image_desc); ?><?php } ?><?php if(!empty($pp_social_sharing) && !empty($pp_lightbox_enable_comment_share)) { ?><br/><br/><br/><br/><a class='button' href='<?php echo get_permalink($photo_id); ?>'><?php _e( 'Comment & share', THEMEDOMAIN ); ?></a><?php } ?>"<?php } ?> class="fancy-gallery" data-fancybox-group="fancybox-thumb" href="<?php echo $image_url[0]; ?>">
    					<img src="<?php echo $small_image_url[0]; ?>" alt="" class=""/>
						<div class="mask">
				            <div class="mask_circle">
						        <i class="fa fa-expand"/></i>
						    </div>
					    </div>
    				</a>
    		<?php
    			}		
    		?>
			</div>
    		
    	</div>
    	
    	<?php
    		$key++;
    		}
    	?>
    	</div>
    	
    	<div class="post_excerpt_full">
		<?php
			global $share_display_inline;
			$share_display_inline = TRUE;
		    //Get Social Share
		    get_template_part("/templates/template-share");
		?>
		</div>
    	
    	</div>
    </div>
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
<br class="clear"/><br/>
<?php get_footer(); ?>