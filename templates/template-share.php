<?php
	$post_type = get_post_type();
	global $page_gallery_id;
	if(!empty($page_gallery_id))
	{
		$post_type = 'galleries';
	}
	
	$show_share = FALSE;

	switch($post_type)
	{
		case 'post':
			$pp_blog_social_sharing = get_option('pp_blog_social_sharing');
		    if(!empty($pp_blog_social_sharing))
		    {
		    	$show_share = TRUE;
		    }
		break;
		
		case 'galleries':
			$pp_social_sharing = get_option('pp_social_sharing');
		    if(!empty($pp_social_sharing))
		    {
		    	$show_share = TRUE;
		    }
		break;
		
		case 'portfolios':
			$pp_portfolio_social_sharing = get_option('pp_portfolio_social_sharing');
		    if(!empty($pp_portfolio_social_sharing))
		    {
		    	$show_share = TRUE;
		    }
		break;
		
		case 'attachment':
			$show_share = TRUE;
		break;
		
		
		case 'product':
			$show_share = FALSE;
		break;
		
		case 'events':
			$show_share = TRUE;
		break;
	}
    
    if($show_share)
    {
    	$pin_thumb = wp_get_attachment_image_src($post->ID, 'gallery_4', true);
    	if(!isset($pin_thumb[0]))
    	{
	    	$pin_thumb[0] = '';
    	}
    	
    	global $share_display_inline;
?>
<div id="social_share_wrapper" <?php if($share_display_inline) { ?>class="inline"<?php } ?>>
	<ul>
		<li><a class="tooltip" title="<?php _e( 'Share On Facebook', THEMEDOMAIN ); ?>" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>"><i class="fa fa-facebook marginright"></i><?php _e( 'Facebook', THEMEDOMAIN ); ?></a></li>
		<li><a class="tooltip" title="<?php _e( 'Share On Twitter', THEMEDOMAIN ); ?>" target="_blank" href="https://twitter.com/intent/tweet?original_referer=<?php echo get_permalink(); ?>&url=<?php echo get_permalink(); ?>"><i class="fa fa-twitter marginright"></i><?php _e( 'Twitter', THEMEDOMAIN ); ?></a></li>
		<li><a class="tooltip" title="<?php _e( 'Share On Pinterest', THEMEDOMAIN ); ?>" target="_blank" href="http://www.pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&media=<?php echo urlencode($pin_thumb[0]); ?>"><i class="fa fa-pinterest marginright"></i><?php _e( 'Pinterest', THEMEDOMAIN ); ?></a></li>
		<li><a class="tooltip" title="<?php _e( 'Share On Google+', THEMEDOMAIN ); ?>" target="_blank" href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>"><i class="fa fa-google-plus marginright"></i><?php _e( 'Google+', THEMEDOMAIN ); ?></a></li>
	</ul>
</div>
<?php
    }
?>