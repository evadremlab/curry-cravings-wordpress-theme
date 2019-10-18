<?php
/**
 * The main template file for display single post page.
 *
 * @package WordPress
*/

get_header(); 

/**
*	Get current page id
**/

$current_page_id = $post->ID;

?>

<br class="clear"/>

<?php
	$page_menu_transparent = get_post_meta($current_page_id, 'post_menu_transparent', true);
	$pp_page_bg = '';
	
	//Check if display event featured imageas background
	$pp_event_ft_bg = get_option('pp_event_ft_bg');
	
	if(!empty($pp_event_ft_bg))
	{
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
	}
?>

<div id="page_caption" <?php if(!empty($pp_page_bg)) { ?>class="hasbg parallax <?php if(empty($page_menu_transparent)) { ?>notransparent<?php } ?>" data-image="<?php echo $background_image; ?>" data-width="<?php echo $background_image_width; ?>" data-height="<?php echo $background_image_height; ?>"<?php } ?>>
	<div class="page_title_wrapper">
		<h1 <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>class ="withtopbar"<?php } ?>><?php the_title(); ?></h1>
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

<div id="page_content_wrapper" class="<?php if(!empty($pp_page_bg)) { ?>hasbg<?php } ?> <?php if(empty($page_menu_transparent)) { ?>notransparent<?php } ?> <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>withtopbar<?php } ?>">
    
    <div class="inner">

    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    	
    	<div class="sidebar_content full_width">

    		<div class="sidebar_content">
					
<?php

global $more; $more = false; # some wordpress wtf logic

if (have_posts()) : while (have_posts()) : the_post();

	$image_thumb = '';
								
	if(!empty($pp_blog_feat_content) && has_post_thumbnail(get_the_ID(), 'large'))
	{
	    $image_id = get_post_thumbnail_id(get_the_ID());
	    $image_thumb = wp_get_attachment_image_src($image_id, 'large', true);
	}
?>
						
<!-- Begin each blog post -->
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="post_wrapper">
	    
	    <div class="post_content_wrapper">
				    
				    <?php
				    	the_content();
						wp_link_pages();
				    ?>
			
	    </div>
	    
	</div>

</div>
<!-- End each blog post -->

<?php comments_template( '' ); ?>

<?php endwhile; endif; ?>
						
    	</div>

    		<div class="sidebar_wrapper">
    		
    			<div class="sidebar_top"></div>
    		
    			<div class="sidebar">
    			
    				<div class="content" style="text-align:center">
    			
    					<?php
							$event_date = get_post_meta(get_the_ID(), 'event_date');
							$event_from_time = get_post_meta(get_the_ID(), 'event_from_time');
							$event_to_time = get_post_meta(get_the_ID(), 'event_to_time');
							$event_address = get_post_meta(get_the_ID(), 'event_address');
						?>
						<h2 class="event_date"><?php echo date('M', strtotime($event_date[0])); ?> <?php echo date('j', strtotime($event_date[0])); ?></h2>
						<div class="event_time">
							<i class="fa fa-clock-o"></i><?php echo $event_from_time[0].' to '.$event_to_time[0]; ?>
						</div>
						<hr class="thick"/><br/>
						
						<?php
							$image_thumb = '';
								
							if(has_post_thumbnail(get_the_ID(), 'large'))
							{
							    $image_id = get_post_thumbnail_id(get_the_ID());
							    $image_thumb = wp_get_attachment_image_src($image_id, 'large', true);
							}
						?>
						
						<?php
						    if(!empty($image_thumb))
						    {
						        $small_image_url = wp_get_attachment_image_src($image_id, 'blog', true);
						?>
						
						    <div class="post_img large static">
						        <img src="<?php echo $small_image_url[0]; ?>" alt="" class="" style="width:<?php echo $small_image_url[1]; ?>px;height:<?php echo $small_image_url[2]; ?>px;"/>
						    </div>
						
						<?php
						    }			
						?>
						
						<?php
							$pp_event_social_sharing = get_option('pp_event_social_sharing');
						
							if(!empty($pp_event_social_sharing))
							{
						    	//Get Social Share
								get_template_part("/templates/template-share");
							}
						?>
    				
						<?php
							//Get event short description
							$event_description = get_post_meta(get_the_ID(), 'event_description');
							
							if(isset($event_description[0]) && !empty($event_description[0]))
							{
						?>
							<div class="event_description"><?php echo $event_description[0]; ?></div>
						<?php
							}
						?>
						<br/>
    				
    				</div>
    		
    			</div>
    			<br class="clear"/>
    	
    			<div class="sidebar_bottom"></div>
    		</div>
    		
    	</div>
    
    </div>
    <!-- End main content -->
   
</div>

<br class="clear"/><br/><br/>
<?php get_footer(); ?>