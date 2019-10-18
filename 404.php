<?php
/**
 * The main template file for display error page.
 *
 * @package WordPress
*/


get_header(); 
?>

<br class="clear"/>

<div id="page_caption">
	<div class="page_title_wrapper">
		<h1><?php _e( 'Page Not Found', THEMEDOMAIN ); ?></h1>
		<?php echo dimox_breadcrumbs(); ?>
	</div>
</div>

<!-- Begin content -->
<div id="page_content_wrapper">

    <div class="inner">
    
    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    	
	    	<div class="sidebar_content full_width nopadding">
	    		<div class="search_form_wrapper">
	    			<h4><?php _e( 'New Search', THEMEDOMAIN ); ?></h4>
	    			<?php _e( "Oops, This Page Could Not Be Found. Try a new search.", THEMEDOMAIN ); ?><br/><br/>
	    			
	    			<form class="searchform" role="search" method="get" action="<?php echo home_url(); ?>">
						<input style="width:96%" type="text" class="field searchform-s" name="s" value="<?php the_search_query(); ?>" title="<?php _e( 'Type and hit enter', THEMEDOMAIN ); ?>">
					</form>
    			</div>	    		
	    	</div>
	    	
	    	<br class="clear"/><br/><br/>
	    	
	    		<h6><?php _e( 'Or try to browse our latest posts instead?', THEMEDOMAIN ); ?></h6>
	    		
	    		<div id="blog_grid_wrapper" class="sidebar_content full_width">
	    		<?php
				global $more; $more = false; 
				
				$query_string ="items=6&post_type=post&paged=$paged";
				query_posts($query_string);
				$key = 0;
				
				if (have_posts()) : while (have_posts()) : the_post();
					
					$animate_layer = $key+7;
					$image_thumb = '';
												
					if(has_post_thumbnail(get_the_ID(), 'large'))
					{
					    $image_id = get_post_thumbnail_id(get_the_ID());
					    $image_thumb = wp_get_attachment_image_src($image_id, 'large', true);
					}
				?>
				
				<!-- Begin each blog post -->
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<div class="post_wrapper grid_layout">
					
						<?php
						    if(!empty($image_thumb))
						   {
						       $small_image_url = wp_get_attachment_image_src($image_id, 'gallery_2', true);
						?>
						
						   <div class="post_img grid">
						       <a href="<?php the_permalink(); ?>">
						       	<img src="<?php echo $small_image_url[0]; ?>" alt="" class="" style="width:<?php echo $small_image_url[1]; ?>px;height:<?php echo $small_image_url[2]; ?>px;"/>
						       	<div class="mask">
						           	<div class="mask_circle grid">
						                   <i class="fa fa-external-link"/></i>
						       		</div>
						           </div>
						       </a>
						   </div>
						
						<?php
						   }
						?>
					    
					    <div class="blog_grid_content">
							<?php
						    	//Check post format
						    	$post_format = get_post_format(get_the_ID());
								
								switch($post_format)
								{
									case 'quote':
							?>
									
									<div class="post_header grid">
										<h6><a href="<?php the_permalink(); ?>"><?php the_content(); ?></a></h6>
										
										<div class="post_detail">
										    <?php echo get_the_time(THEMEDATEFORMAT); ?>&nbsp;
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
									
									<div class="post_header grid">
										<h6>
											<?php the_content(); ?>
										</h6>
											
										<div class="post_detail">
										    <?php echo get_the_time(THEMEDATEFORMAT); ?>&nbsp;/&nbsp;
										    <?php
										    	$author_ID = get_the_author_meta('ID');
										    	$author_name = get_the_author();
										    	$author_url = get_author_posts_url($author_ID);
										    	
										    	if(!empty($author_name))
										    	{
										    ?>
										    	<?php echo _e( 'By', THEMEDOMAIN ); ?>&nbsp;<a href="<?php echo $author_url; ?>"><?php echo $author_name; ?></a>&nbsp;/&nbsp;
										    <?php
										    	}
										    ?>
										    <a href="<?php comments_link(); ?>"><?php comments_number(__('No Comment', THEMEDOMAIN), __('1 Comment', THEMEDOMAIN), __('% Comments', THEMEDOMAIN)); ?></a>
										</div>
									</div>
							<?php
									break;
									
									default:
						    ?>
						    
							    <div class="post_header grid">
							    	<h6><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h6>
							    	
							    	<div class="post_detail">
								    	<?php echo get_the_time(THEMEDATEFORMAT); ?>&nbsp;/&nbsp;
								    	<?php
											$author_ID = get_the_author_meta('ID');
									    	$author_name = get_the_author();
									    	$author_url = get_author_posts_url($author_ID);
											
											if(!empty($author_name))
											{
										?>
											<?php echo _e( 'By', THEMEDOMAIN ); ?>&nbsp;<a href="<?php echo $author_url; ?>"><?php echo $author_name; ?></a>&nbsp;/&nbsp;
										<?php
											}
								    	?>
									    <a href="<?php comments_link(); ?>"><?php comments_number(__('No Comment', THEMEDOMAIN), __('1 Comment', THEMEDOMAIN), __('% Comments', THEMEDOMAIN)); ?></a>
									</div>
								    <a class="readmore grid" href="<?php the_permalink(); ?>"><?php echo _e( 'Read More', THEMEDOMAIN ); ?></a>
							    </div>
						    <?php
						    		break;
						    	}
						    ?>
					    </div>
					    
					</div>
				
				</div>
				<!-- End each blog post -->
				
				<?php $key++; ?>
				<?php endwhile; endif; ?>
	    		</div>
    		
    	</div>
    	
    </div>
</div>
<br class="clear"/><br/><br/>
<?php get_footer(); ?>