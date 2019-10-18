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
	
	//Get page featured image
	if(has_post_thumbnail($current_page_id, 'full'))
	{
	    $image_id = get_post_thumbnail_id($current_page_id); 
	    $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
	    
	    $background_image = $image_thumb[0];
		$background_image_width = $image_thumb[1];
		$background_image_height = $image_thumb[2];
	}
	
	//Check if display post featured imageas background
	$pp_blog_ft_bg = get_option('pp_blog_ft_bg');
	
	if(!empty($pp_blog_ft_bg))
	{
		//Get page featured image
		if(has_post_thumbnail($current_page_id, 'full'))
		{
		    $pp_page_bg = $image_thumb[0];
		}
	}
?>

<div id="page_caption" <?php if(!empty($pp_page_bg)) { ?>class="hasbg parallax <?php if(empty($page_menu_transparent)) { ?>notransparent<?php } ?>" data-image="<?php echo $background_image; ?>" data-width="<?php echo $background_image_width; ?>" data-height="<?php echo $background_image_height; ?>"<?php } ?>>
	<div class="page_title_wrapper">
		<h1 <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>class ="withtopbar"<?php } ?>><?php _e( 'Blog', THEMEDOMAIN ); ?></h1>
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
//If display feat content
$pp_blog_feat_content = get_option('pp_blog_feat_content');

$post_gallery_id = '';
if(!empty($pp_blog_feat_content))
{
	$post_gallery_id = get_post_meta($current_page_id, 'post_gallery_id', true);
}
?>
<div id="page_content_wrapper" class="<?php if(!empty($pp_page_bg)) { ?>hasbg<?php } ?> <?php if(empty($page_menu_transparent)) { ?>notransparent<?php } ?> <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>withtopbar<?php } ?>">
    
    <div class="inner">

    	<!-- Begin main content -->
    	<div class="inner_wrapper">

	    	<div class="sidebar_content full_width">
					
<?php
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

	<div class="post_wrapper full">
	
		<?php
		    //Get post featured content
		    $post_ft_type = get_post_meta(get_the_ID(), 'post_ft_type', true);
		    
		    switch($post_ft_type)
		    {
		    	case 'Image':
		    	default:
		        	if(!empty($image_thumb))
		        	{
		        		$large_image_url = wp_get_attachment_image_src($image_id, 'original', true);
		        		$small_image_url = wp_get_attachment_image_src($image_id, 'blog_l', true);
		?>
		
		    	    <div class="post_img large">
		    	    	<a href="<?php echo $large_image_url[0]; ?>" class="img_frame">
		    	    		<img src="<?php echo $small_image_url[0]; ?>" alt="" class="" style="width:<?php echo $small_image_url[1]; ?>px;height:<?php echo $small_image_url[2]; ?>px;"/>
		        	    	<div class="mask">
		                    	<div class="mask_circle">
		    	                    <i class="fa fa-expand"/></i>
		    					</div>
		                    </div>
		                </a>
		    	    </div>
		
		<?php
		    		}
		    	break;
		    	
		    	case 'Vimeo Video':
		    		$post_ft_vimeo = get_post_meta(get_the_ID(), 'post_ft_vimeo', true);
		?>
		    		<?php echo do_shortcode('[tg_vimeo video_id="'.$post_ft_vimeo.'" width="670" height="377"]'); ?>
		<?php
		    	break;
		    	
		    	case 'Youtube Video':
		    		$post_ft_youtube = get_post_meta(get_the_ID(), 'post_ft_youtube', true);
		?>
		    		<?php echo do_shortcode('[tg_youtube video_id="'.$post_ft_youtube.'" width="670" height="377"]'); ?>
		<?php
		    	break;
		    	
		    	case 'Gallery':
		    		$post_ft_gallery = get_post_meta(get_the_ID(), 'post_ft_gallery', true);
		?>
		    		<?php echo do_shortcode('[tg_gallery_slider gallery_id="'.$post_ft_gallery.'" width="670" height="270"]'); ?>
		<?php
		    	break;
		    	
		    } //End switch
		?>
	    
	    <?php
		    	//Check post format
		    	$post_format = get_post_format(get_the_ID());
				
				switch($post_format)
				{
					case 'quote':
			?>
					
					<i class="post_qoute_mark fa fa-quote-right"></i>
					
					<div class="post_header large">
						<h5>
							<a href="<?php the_permalink(); ?>"><?php the_content(); ?></a>
						</h5>
							
					    <div class="post_detail">
					    	<?php echo _e( 'Posted On', THEMEDOMAIN ); ?>&nbsp;<?php echo get_the_time(THEMEDATEFORMAT); ?>&nbsp;
					    	<?php
					    		$author_ID = get_the_author_meta('ID');
					        	$author_name = get_the_author();
					        	$author_url = get_author_posts_url($author_ID);
					    		
					    		if(!empty($author_name))
					    		{
					    	?>
					    		<?php echo _e( 'By', THEMEDOMAIN ); ?>&nbsp;<a href="<?php echo $author_url; ?>"><?php echo $author_name; ?></a>&nbsp;
					    	<?php
					    		}
					    	?>
					        <?php echo _e( 'And has', THEMEDOMAIN ); ?>&nbsp;<a href="<?php comments_link(); ?>"><?php comments_number(__('No Comment', THEMEDOMAIN), __('1 Comment', THEMEDOMAIN), __('% Comments', THEMEDOMAIN)); ?></a>
					    </div>
					</div>
			<?php
					break;
					
					case 'link':
			?>
					
					<i class="post_qoute_mark fa fa-link"></i>
					
					<div class="post_header large">
						<h5>
							<?php the_content(); ?>
						</h5>
							
					    <div class="post_detail">
					    	<?php echo _e( 'Posted On', THEMEDOMAIN ); ?>&nbsp;<?php echo get_the_time(THEMEDATEFORMAT); ?>&nbsp;
					    	<?php
					    		$author_ID = get_the_author_meta('ID');
					        	$author_name = get_the_author();
					        	$author_url = get_author_posts_url($author_ID);
					    		
					    		if(!empty($author_name))
					    		{
					    	?>
					    		<?php echo _e( 'By', THEMEDOMAIN ); ?>&nbsp;<a href="<?php echo $author_url; ?>"><?php echo $author_name; ?></a>&nbsp;
					    	<?php
					    		}
					    	?>
					        <?php echo _e( 'And has', THEMEDOMAIN ); ?>&nbsp;<a href="<?php comments_link(); ?>"><?php comments_number(__('No Comment', THEMEDOMAIN), __('1 Comment', THEMEDOMAIN), __('% Comments', THEMEDOMAIN)); ?></a>
					    </div>
					</div>
			<?php
					break;
					
					default:
		    ?>
		    
			    <div class="post_header large">
			    	<h5><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h5>
			    	
			    	<div class="post_detail">
				    	<?php echo _e( 'Posted On', THEMEDOMAIN ); ?>&nbsp;<?php echo get_the_time(THEMEDATEFORMAT); ?>&nbsp;
				    	<?php
							$author_ID = get_the_author_meta('ID');
					    	$author_name = get_the_author();
					    	$author_url = get_author_posts_url($author_ID);
							
							if(!empty($author_name))
							{
						?>
							<?php echo _e( 'By', THEMEDOMAIN ); ?>&nbsp;<a href="<?php echo $author_url; ?>"><?php echo $author_name; ?></a>&nbsp;
						<?php
							}
				    	?>
					    <?php echo _e( 'And has', THEMEDOMAIN ); ?>&nbsp;<a href="<?php comments_link(); ?>"><?php comments_number(__('No Comment', THEMEDOMAIN), __('1 Comment', THEMEDOMAIN), __('% Comments', THEMEDOMAIN)); ?></a>
					</div>
					<br class="clear"/>
				    
				    <?php
				    	the_content();
				    	wp_link_pages();
				    ?>
				    
				    <?php
					    if(has_tag())
					    {
					?>
					    <div class="post_excerpt post_tag" style="text-align:left">
					    	<i class="fa fa-tags"></i>
					    	<?php the_tags('', '', '<br />'); ?>
					    </div>
					<?php
					    }
					?>
					
					<?php
					    //Get Social Share
					    get_template_part("/templates/template-share");
					?>
			    </div>
		    <?php
		    		break;
		    	}
		    ?>
			<br class="clear"/>
			<?php
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
			?>
			<div class="blog_next_prev_wrapper">
			   <div class="post_previous">
			      	<?php
			    	    //Get Previous Post
			    	    if (!empty($prev_post)): 
			    	    	$prev_image_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($prev_post->ID), 'thumbnail', true);
			    	    	if(isset($prev_image_thumb[0]))
			    	    	{
								$image_file_name = basename($prev_image_thumb[0]);
			    	    	}
			    	?>
			      		<span class="post_previous_icon"><i class="fa fa-angle-left"></i></span>
			      		<div class="post_previous_content">
			      			<h6><?php echo _e( 'Previous Article', THEMEDOMAIN ); ?></h6>
			      			<strong><a <?php if(isset($prev_image_thumb[0]) && $image_file_name!='default.png') { ?>class="post_prev_next_link" data-img="<?php echo $prev_image_thumb[0]; ?>"<?php } ?> href="<?php echo get_permalink( $prev_post->ID ); ?>"><?php echo $prev_post->post_title; ?></a></strong>
			      		</div>
			      	<?php endif; ?>
			   </div>
			<span class="separated"></span>
			   <div class="post_next">
			   		<?php
			    	    //Get Next Post
			    	    if (!empty($next_post)): 
			    	    	$next_image_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($next_post->ID), 'thumbnail', true);
			    	    	if(isset($next_image_thumb[0]))
			    	    	{
								$image_file_name = basename($next_image_thumb[0]);
			    	    	}
			    	?>
			      		<span class="post_next_icon"><i class="fa fa-angle-right"></i></span>
			      		<div class="post_next_content">
			      			<h6><?php echo _e( 'Next Article', THEMEDOMAIN ); ?></h6>
			      			<strong><a <?php if(isset($prev_image_thumb[0]) && $image_file_name!='default.png') { ?>class="post_prev_next_link" data-img="<?php echo $next_image_thumb[0]; ?>"<?php } ?> href="<?php echo get_permalink( $next_post->ID ); ?>"><?php echo $next_post->post_title; ?></a></strong>
			      		</div>
			      	<?php endif; ?>
			   </div>
			</div>
			<?php
			    	//If has previous or next post then add line break
			    	if(!empty($prev_post) OR !empty($next_post))
			    	{
			    		echo '<br/>';
			    	}
			?>
			<?php
			    }
			?>
			
			<?php
			    $pp_blog_display_related = get_option('pp_blog_display_related');
			    
			    if($pp_blog_display_related)
			    {
			?>
			
			<?php
			//for use in the loop, list 9 post titles related to post's tags on current post
			$tags = wp_get_post_tags($post->ID);
			
			if ($tags) {
			
			    $tag_in = array();
			  	//Get all tags
			  	foreach($tags as $tags)
			  	{
			      	$tag_in[] = $tags->term_id;
			  	}
			
			  	$args=array(
			      	  'tag__in' => $tag_in,
			      	  'post__not_in' => array($post->ID),
			      	  'showposts' => 4,
			      	  'ignore_sticky_posts' => 1,
			      	  'orderby' => 'date',
			      	  'order' => 'DESC'
			  	 );
			  	$my_query = new WP_Query($args);
			  	$i_post = 1;
			  	
			  	if( $my_query->have_posts() ) {
			  	  	echo '<h5><span>'.__( 'You might also like', THEMEDOMAIN ).'</span></h5><br class="clear"/>';
			 ?>
			 	<ul class="posts blog">
			    	 <?php
			    	 	global $have_related;
			    	    while ($my_query->have_posts()) : $my_query->the_post();
			    	    $have_related = TRUE; 
			    	 ?>
			    	    <li>
			    	    	<?php
			    	    		if(has_post_thumbnail($post->ID, 'thumbnail'))
			    				{
			    					$image_id = get_post_thumbnail_id($post->ID);
			    					$image_url = wp_get_attachment_image_src($image_id, 'thumbnail', true);
			    	    	?>
			    	    	<div class="post_circle_thumb">
			    	    		<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><img class="post_ft" src="<?php echo $image_url[0]; ?>" alt="<?php the_title(); ?>"/></a>
			    	    	</div>
			    	    	<?php
			    	    		}
			    	    	?>
			    	    	<a href="<?php the_permalink() ?>"><?php the_title(); ?></a><br/>
			    	    	<span class="post_attribute"><?php echo date(THEMEDATEFORMAT, strtotime($post->post_date)); ?></span>
			    		</li>
			    	  <?php
			    	  		$i_post++;
			    			endwhile;
			    			    
			    			wp_reset_query();
			    	  ?>
			      
			  	</ul>
			    <br class="clear"/><br/>
			<?php
			  	}
			}
			?>
			
			<?php
			    } //end if show related
			?>
	    </div>
	    
	</div>

</div>
<!-- End each blog post -->
<div class="fullwidth_comment_wrapper portfolio_comment">
	<?php comments_template( '' ); ?>
</div>

<?php endwhile; endif; ?>
    	
    	</div>
    
    </div>
    <!-- End main content -->
   
</div>

<?php
	global $prev_post;
	
    //Get More Story Module
    $pp_blog_more_story = get_option('pp_blog_more_story');
    
    if(!empty($prev_post) && !empty($pp_blog_more_story))
    {
    	$post_more_image = '';
    	if(has_post_thumbnail(get_the_ID(), 'blog_g'))
    	{
    	    $post_more_image_id = get_post_thumbnail_id($prev_post->ID);
    	    $post_more_image = wp_get_attachment_image_src($post_more_image_id, 'blog_g', true);
    	}
?>
<div id="post_more_wrapper" class="hiding">
    <a href="javascript:;" id="post_more_close"><i class="fa fa-times-circle"></i></a>
    <h5><span><?php _e( 'More Story', THEMEDOMAIN ); ?></span></h5><br/>
    <?php
    	if(!empty($post_more_image))
    	{
    ?>
    <div class="post_img grid">
	    <a href="<?php echo get_permalink($prev_post->ID); ?>">
	    	<img src="<?php echo $post_more_image[0]; ?>" alt="" class="" style="width:<?php echo $post_more_image[1]; ?>px;height:<?php echo $post_more_image[2]; ?>px;"/>
	    	<div class="mask">
            	<div class="mask_circle">
	            	<i class="fa fa-share"/></i>
	    		</div>
	        </div>
	    </a>
	</div>
    <?php
    	}
    ?>
    <a class="post_more_title" href="<?php echo get_permalink($prev_post->ID); ?>">
    	<h6 style="margin-top:-5px"><?php echo $prev_post->post_title; ?></h6>
    </a>
    <?php echo pp_substr(strip_tags(strip_shortcodes($prev_post->post_content)), 90); ?>
    
    <br/><br/>
	<hr/>
	<div class="post_detail">
	    <?php echo date(THEMEDATEFORMAT, strtotime($prev_post->post_date)); ?>
	</div>
	<a class="read_more" href="<?php echo get_permalink($prev_post->ID); ?>"><?php echo _e( 'Read More', THEMEDOMAIN ); ?></a>
	<br class="clear"/><hr/>
</div>
<?php
    }
?> 
<br class="clear"/><br/>
<?php get_footer(); ?>