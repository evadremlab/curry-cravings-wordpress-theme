<?php
/**
 * Template Name: Menu Classic 2 Columns
 * The main template file for display menu page.
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

get_header();
?>

<br class="clear"/>

<?php
    //Include custom header feature
	get_template_part("/templates/template-header");
?>

<!-- Begin content -->
<?php
	//Get number of menu items per page
	$pp_menu_items_page = get_option('pp_menu_items_page');
	if(empty($pp_menu_items_page))
	{
		$pp_menu_items_page = 12;
	}
	
	//Get all menu items for paging
	global $wp_query;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	$query_string = 'paged='.$paged.'&orderby=menu_order&order=ASC&post_type=menus&numberposts=-1&suppress_filters=0&posts_per_page='.$pp_menu_items_page;
	if(!empty($term))
	{
	    $query_string .= '&posts_per_page=-1&menucats='.$term;
	}
	
	$page_menu_cat = get_post_meta($current_page_id, 'page_menu_cat', true);

	if(!empty($page_menu_cat))
	{
		$query_string .= '&posts_per_page=-1&menucats='.$page_menu_cat;
	}
	query_posts($query_string);
?>
    
<div class="inner">

	<div class="inner_wrapper">
	
	<?php
	    if(!empty($post->post_content) && empty($term))
	    {
	?>
	    <?php echo tg_apply_content($post->post_content); ?>
	<?php
	    }
	    elseif(!empty($term))
	    { 
	?>
	    <?php echo tg_apply_content($obj_term->description); ?>
	<?php
	    }
	?>
	
	<div id="page_main_content" class="sidebar_content full_width nopadding">
	
	<div id="portfolio_filter_wrapper" class="gallery two_cols portfolio-content section content clearfix">
	
	<?php
		$key = 0;
		if (have_posts()) : while (have_posts()) : the_post();
			$key++;
			$image_url = '';
			$menu_ID = get_the_ID();
					
			if(has_post_thumbnail($menu_ID, 'large'))
			{
			    $image_id = get_post_thumbnail_id($menu_ID);
			    $image_url = wp_get_attachment_image_src($image_id, 'large', true);
			    
			    $small_image_url = wp_get_attachment_image_src($image_id, 'gallery_2', true);
			}
			
			$menu_price = get_post_meta($menu_ID, 'menu_price');
			if(!isset($menu_price[0]))
			{
				$menu_price[0] = 0;
			}
			$menu_price_currency = get_post_meta($menu_ID, 'menu_price_currency');
			if(!isset($menu_price_currency[0]))
			{
				$menu_price_currency[0] = '$';
			}
			$menu_highlight = get_post_meta($menu_ID, 'menu_highlight');
			$menu_highlight_title = get_post_meta($menu_ID, 'menu_highlight_title');
	?>
	<div class="element classic2_cols">
	
		<div class="one_half gallery2 static filterable gallery_type animated<?php echo $key+1; ?>" data-id="post-<?php echo $key+1; ?>">
			<?php 
				if(!empty($image_url[0]))
				{
			?>		
				<a data-title="<strong><?php echo get_the_title(); ?></strong>" href="<?php echo $image_url[0]; ?>" class="fancy-gallery">
				    <img src="<?php echo $small_image_url[0]; ?>" alt="" />
				
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
		<br class="clear"/><br/>
		<div id="portfolio_desc_<?php echo $menu_ID; ?>" class="portfolio_desc portfolio2 textleft">
            <h5 class="menu_post">
            	<span class="menu_title"><?php echo get_the_title(); ?></span>
            	<span class="menu_dots"></span>
            	<span class="menu_price"><?php echo $menu_price_currency[0]; ?><?php echo $menu_price[0]; ?></span>
            </h5>
            <div class="post_detail menu_excerpt"><?php echo get_the_excerpt(); ?></div>
            <?php
            	if(!empty($menu_highlight) && !empty($menu_highlight_title))
				{
            ?>
            <div class="menu_highlight"><?php echo $menu_highlight_title[0]; ?></div>
            <?php
            	}
            ?>
        </div>
	</div>
	<?php
		endwhile; endif;
	?>
		
	</div>
	
	<?php
	    if($wp_query->max_num_pages > 1)
	    {
	    	if (function_exists("wpapi_pagination")) 
	    	{
	?>
			<br class="clear"/><br/><br/>
	<?php
	    	    wpapi_pagination($wp_query->max_num_pages);
	    	}
	    	else
	    	{
	    	?>
	    	    <div class="pagination"><p><?php posts_nav_link(' '); ?></p></div>
	    	<?php
	    	}
	    ?>
	    <div class="pagination_detail">
	     	<?php
	     		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	     	?>
	     	<?php _e( 'Page', THEMEDOMAIN ); ?> <?php echo $paged; ?> <?php _e( 'of', THEMEDOMAIN ); ?> <?php echo $wp_query->max_num_pages; ?>
	     </div>
	     <?php
	     }
	?>
	</div>

</div>
</div>
<br class="clear"/>
<?php get_footer(); ?>
    
<!-- End content -->