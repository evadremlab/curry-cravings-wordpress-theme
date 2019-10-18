<?php
/**
 * Template Name: Event 4 Columns
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

get_header();

wp_enqueue_style("jquery-ui", get_template_directory_uri()."/functions/jquery-ui/css/custom-theme/jquery-ui-1.8.24.custom.css", false, "1.0", "all");
wp_enqueue_script("jquery-ui-datepicker");
wp_enqueue_script('custom-date', get_template_directory_uri()."/js/custom-date.js", false, THEMEVERSION, true);
?>
<br class="clear"/>

<?php
    //Include custom header feature
	get_template_part("/templates/template-header");
?>

<!-- Begin content -->
<?php
	//Get number of event per page
	$pp_event_items_page = get_option('pp_event_items_page');
	if(empty($pp_event_items_page))
	{
		$pp_event_items_page = 9;
	}
	
	//Get all portfolio items for paging
	global $wp_query;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	if(!isset($_GET['date_raw']))
	{
		if(THEMEDEMO)
		{
			$current_time = 0;
		}
		else
		{
			$current_time = time()-(3600*24);
		}
	}
	else
	{
		$current_time = $_GET['date_raw'];
	}
	
	if(!empty($term))
	{
	    $query_string .= '&posts_per_page=-1&eventscats='.$term;
	}
	else
	{
		$query_string .= '&posts_per_page='.$pp_event_items_page;
	}
	//query_posts($query_string);
	
	$args = array(
	    'post_type' => 'events',
	    'paged' => $paged,
	    'order' => 'ASC',
	    'suppress_filters' => 0,
	    'posts_per_page' => $pp_event_items_page,
	    'meta_query' => array(
	        array(
	            'key' => 'event_date_raw',
	            'value' => $current_time,
	            'compare' => '>'
	        ),
	    )
	);
	
	if(isset($_GET['date_raw']))
	{
		$args['numberposts'] = -1;
	}
	
	if(isset($_GET['keyword']))
	{
		$args['wpse18703_title'] = $_GET['keyword'];
	}
	
 	query_posts($args);
?>
    
<div class="inner">

	<div class="inner_wrapper">
	
	<div id="page_main_content" class="sidebar_content full_width nopadding">
	
	<?php
		//Check if display filterable option
		$pp_event_filter = get_option('pp_event_filter');
		
		if(!empty($pp_event_filter))
		{
	?>
	<div class="search_form_wrapper">
		<?php
			$search_date = '';
			if(isset($_GET['date']))
			{
				$search_date = $_GET['date'];
			}
			$search_date_raw = '';
			if(isset($_GET['date_raw']))
			{
				$search_date_raw = $_GET['date_raw'];
			}
			$search_keyword = '';
			if(isset($_GET['keyword']))
			{
				$search_keyword = $_GET['keyword'];
			}
		?>
		<h4><?php _e( 'Filter Event By', THEMEDOMAIN ); ?></h4>
	    <form class="searchform" role="search" method="get" action="">
	    	<input style="width:40%" type="text" class="field searchform-s pp_date" id="date" name="date" value="<?php echo $search_date; ?>" placeholder="<?php _e( 'From Date', THEMEDOMAIN ); ?>">
	    	<input type="hidden" id="date_raw" name="date_raw" value="<?php echo $search_date_raw; ?>"/>
	    	<input style="width:40%" type="text" class="field searchform-s" id="keyword" name="keyword" value="<?php echo $search_keyword; ?>" placeholder="<?php _e( 'Keywords', THEMEDOMAIN ); ?>">
	    	<button type="submit" id="searchsubmit" class="button submit">
	            <i class="fa fa-search"></i>
	        </button>
		</form>
    </div>
    <?php
    	}
    ?>
	
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
    	
    	<div id="portfolio_filter_wrapper" class="four_cols gallery section content clearfix">
	
	<?php
		$key = 0;
		if (have_posts()) : while (have_posts()) : the_post();
			$key++;
			$image_url = '';
			$event_ID = get_the_ID();
					
			if(has_post_thumbnail($event_ID, 'large'))
			{
			    $image_id = get_post_thumbnail_id($event_ID);
			    $image_url = wp_get_attachment_image_src($image_id, 'large', true);
			    
			    $small_image_url = wp_get_attachment_image_src($image_id, 'gallery_2', true);
			}
			
			$permalink_url = get_permalink($event_ID);
	?>
	<div class="element classic4_cols">
	
		<div class="one_fourth gallery4 filterable gallery_type static animated<?php echo $key+1; ?>" data-id="post-<?php echo $key+1; ?>">
			<?php 
				if(!empty($image_url[0]))
				{
			?>		
				<a data-title="<strong><?php echo get_the_title(); ?></strong><?php echo remove_shortcode(get_the_content()); ?>" href="<?php echo $image_url[0]; ?>" class="fancy-gallery">
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
	
		<br class="clear"/>
		<div id="portfolio_desc_<?php echo $event_ID; ?>" class="portfolio_desc portfolio4 filterable">
            <?php
				$event_date = get_post_meta(get_the_ID(), 'event_date');
				$event_from_time = get_post_meta(get_the_ID(), 'event_from_time');
				$event_to_time = get_post_meta(get_the_ID(), 'event_to_time');
				$event_address = get_post_meta(get_the_ID(), 'event_address');
			?>
			<div class="event_date_wrapper">
				<h2 class="event_date"><?php echo date('M', strtotime($event_date[0])); ?> <?php echo date('j', strtotime($event_date[0])); ?></h2>
				<div class="event_title"><?php echo get_the_title(); ?></div>
			</div>
			<hr class="thick"/><br/>
            <?php echo get_the_excerpt(); ?><br/>
            <a class="readmore grid" href="<?php the_permalink(); ?>"><?php echo _e( 'Read More', THEMEDOMAIN ); ?></a>
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