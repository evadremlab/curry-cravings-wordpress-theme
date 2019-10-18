<?php
/**
 * The main template file for display fullscreen vimeo video
 *
 * @package WordPress
 */

$portfolio_video_id = get_post_meta($post->ID, 'portfolio_video_id', true);

//important to apply dynamic header & footer style
global $pp_homepage_style;
$pp_homepage_style = 'fullscreen_video';

get_header();

//Check if display galery info
$pp_gallery_auto_info = get_option('pp_gallery_auto_info');
?>

<div id="imageFlow_gallery_info" class="fadeIn" <?php if(empty($pp_gallery_auto_info)) { ?>style="left:-370px"<?php } ?>>
	<div class="imageFlow_gallery_info_wrapper">
		<h1><?php the_title(); ?></h1>
		<div class="imageFlow_gallery_info_author">
			<?php _e( 'Posted On', THEMEDOMAIN ); ?> <?php echo get_the_time(THEMEDATEFORMAT); ?>
		</div>
		<br/><br/><hr/>
		<?php
			$portoflio_post_content = get_post_field('post_content', $post->ID);
    		
    		if(!empty($portoflio_post_content))
    		{
    	?>
			<br/><div class="page_caption_desc"><?php echo $portoflio_post_content; ?></div>
		<?php
			}
		?>
		
		<a id="flow_view_button" class="button" href="#"><?php _e( 'View Video', THEMEDOMAIN ); ?></a>
		
		<?php
		    $pp_portfolio_next_prev = get_option('pp_portfolio_next_prev');
		    if(!empty($pp_portfolio_next_prev))
		    {
		
		    $args = array(
		    	'before'           => '<p>' . __('Pages:', THEMEDOMAIN),
		    	'after'            => '</p>',
		    	'link_before'      => '',
		    	'link_after'       => '',
		    	'next_or_number'   => 'number',
		    	'nextpagelink'     => __('Next page', THEMEDOMAIN),
		    	'previouspagelink' => __('Previous page', THEMEDOMAIN),
		    	'pagelink'         => '%',
		    	'echo'             => 1
		    );
		    wp_link_pages($args);
		
		    $pp_blog_next_prev = get_option('pp_blog_next_prev');
		    
		    if($pp_blog_next_prev)
		    {
		?>
		<?php
		    	//Get Previous and Next Post
		    	$prev_post = get_previous_post();
		    	$next_post = get_next_post();
		    	
		    	//If has previous or next post then add line break
		    	if(!empty($prev_post) OR !empty($next_post))
		    	{
		    		echo '<br class="clear"/><br/>';
		    	}
		?>
		<div class="portfolio_next_prev_wrapper video">
			   <?php
			       //Get Previous Post
			       if (!empty($prev_post)): 
			       	$prev_image_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($prev_post->ID), 'thumbnail', true);
			       	if(isset($prev_image_thumb[0]))
			       	{
			       		$image_file_name = basename($prev_image_thumb[0]);
			       	}
			   ?>
			       <a class="portfolio_prev <?php if(isset($prev_image_thumb[0]) && $image_file_name!='default.png') { ?>portfolio_prev_next_link<?php } ?>" <?php if(isset($prev_image_thumb[0]) && $image_file_name!='default.png') { ?>data-img="<?php echo $prev_image_thumb[0]; ?>"<?php } ?> href="<?php echo get_permalink( $prev_post->ID ); ?>" data-title="<?php echo $prev_post->post_title; ?>" data-target=""><i class="fa fa-chevron-left"></i></a>
			   <?php endif; ?>
			   <?php
			       //Get Next Post
			       if (!empty($next_post)): 
			       	$next_image_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($next_post->ID), 'thumbnail', true);
			       	if(isset($next_image_thumb[0]))
			       	{
			       		$image_file_name = basename($next_image_thumb[0]);
			       	}
			   ?>
			       <a class="portfolio_next <?php if(isset($next_image_thumb[0]) && $image_file_name!='default.png') { ?>portfolio_prev_next_link<?php } ?>" <?php if(isset($next_image_thumb[0]) && $image_file_name!='default.png') { ?>data-img="<?php echo $next_image_thumb[0]; ?>"<?php } ?> href="<?php echo get_permalink( $next_post->ID ); ?>" data-title="<?php echo $next_post->post_title; ?>" data-target=""><i class="fa fa-chevron-right"></i></a>
			   <?php endif; ?>
			</div>
		<?php
		    }
		    
		    //If has previous or next post then add line break
		    if(!empty($prev_post) OR !empty($next_post))
		    {
		        echo '<br class="clear"/><br/>';
		    }
		    
		} //End if display previous and next portfolios
		?>
	</div>
</div>
<a id="flow_info_button" <?php if(empty($pp_gallery_auto_info)) { ?>style="display:block;"<?php } ?> href="#"><i class="fa fa-info-circle"></i></a>

<div id="vimeo_bg">
	<iframe frameborder="0" src="http://player.vimeo.com/video/<?php echo $portfolio_video_id; ?>?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1&amp;loop=0" webkitallowfullscreen="" allowfullscreen=""></iframe>
</div>

<?php
	get_footer();
?>