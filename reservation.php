<?php
/**
 * Template Name: Reservation
 * The main template file for display contact page.
 *
 * @package WordPress
*/

/**
*	Get Current page object
**/
$current_page = get_page($post->ID);
$current_page_id = '';

if(isset($current_page->ID))
{
    $current_page_id = $current_page->ID;
}

//Get Page Sidebar
$page_sidebar = get_post_meta($current_page_id, 'page_sidebar', true);
if(empty($page_sidebar))
{
	$page_sidebar = 'Contact Sidebar';
}

get_header(); 

wp_enqueue_style("jquery-ui", get_template_directory_uri()."/functions/jquery-ui/css/custom-theme/jquery-ui-1.8.24.custom.css", false, "1.0", "all");
wp_enqueue_style("jquery.timepicker", get_template_directory_uri()."/functions/jquery.timepicker.css", false, "1.0", "all");

wp_enqueue_script("jquery-ui-datepicker");
wp_enqueue_script('custom-date', get_template_directory_uri()."/js/custom-date.js", false, THEMEVERSION, true);
wp_enqueue_script("jquery.timepicker", get_template_directory_uri()."/functions/jquery.timepicker.js", false);
wp_enqueue_script('custom-time', get_template_directory_uri()."/js/custom-time.js", false, THEMEVERSION, true);
?>
<br class="clear"/>
<?php
	//Include custom header feature
	get_template_part("/templates/template-header");
?>

    <div class="inner">
    
    	<div class="inner_wrapper">
    
	    <div class="sidebar_content full_width">
	    
	    	<div class="sidebar_content">
	    
	    	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>		

    			<?php the_content(); ?>

    		<?php endwhile; ?>
	    	
	    	<!-- Begin main content -->
    			<?php
    				wp_enqueue_script("jquery.validate", get_template_directory_uri()."/js/jquery.validate.js", false, THEMEVERSION, true);
    				
    				wp_register_script("script-reservation-form", get_template_directory_uri()."/templates/script-reservation-form.php", false, THEMEVERSION, true);
					$params = array(
					  'ajaxurl' => admin_url('admin-ajax.php'),
					  'ajax_nonce' => wp_create_nonce('tgajax-post-contact-nonce'),
					);
					wp_localize_script( 'script-reservation-form', 'tgAjax', $params );
					wp_enqueue_script("script-reservation-form", get_template_directory_uri()."/templates/script-reservation-form.php", false, THEMEVERSION, true);
    			?>
    			<div id="reponse_msg"><ul></ul></div>
    			
    			<form id="reservation_form" method="post" action="/wp-admin/admin-ajax.php">
	    			<input type="hidden" id="action" name="action" value="pp_reservation_mailer"/>

    				<h5 class="order_title"><span class="order_number">1</span><?php _e( "How many people?", THEMEDOMAIN ); ?></h5><br/>
    				<label for="order_no_people" class="hidden"><?php echo _e( 'Number of People', THEMEDOMAIN ); ?></label>
			        <select id="order_no_people" name="order_no_people" class="required_field" style="width:99%">
			        	<?php
			        		for ($i = 1; $i < 21 ; $i++) {
			        			$option_title = $i;
			        			if($i==1)
			        			{
				        			$option_title.=  ' '.__( 'person', THEMEDOMAIN );
			        			}
			        			elseif($i<20)
			        			{
				        			$option_title.=  ' '.__( 'people', THEMEDOMAIN );
			        			}
			        			else
			        			{
				        			$option_title.=  '+ '.__( 'people', THEMEDOMAIN );
			        			}
			        	?>
			        		<option value="<?php echo $i; ?>"><?php echo $option_title; ?></option>
			        	<?php
			        		}
			        	?>
			        </select>
			        <br/><br/><br/>
			        
			        <h5 class="order_title"><span class="order_number">2</span><?php _e( "When would you like to come?", THEMEDOMAIN ); ?></h5><br/>
			        <label for="order_date" class="hidden"><?php echo _e( 'Date', THEMEDOMAIN ); ?></label>
			    	<input type="text" class="pp_date required_field" id="order_date" name="order_date" placeholder="<?php _e( 'Select Date', THEMEDOMAIN ); ?>">
			    	<label for="order_time" class="hidden"><?php echo _e( 'Time', THEMEDOMAIN ); ?></label>
			    	<input type="text" class="pp_time required_field" id="order_time" name="order_time" placeholder="<?php _e( 'Select Time', THEMEDOMAIN ); ?>">
			    	<br/><br/><br/>
			    	
			    	<h5 class="order_title"><span class="order_number">3</span><?php _e( "Additional Details & Special Requests", THEMEDOMAIN ); ?></h5><br/>
			    	<label for="message"><?php echo _e( 'Special Requests', THEMEDOMAIN ); ?></label>
			        <textarea id="message" name="message" rows="5" cols="10" style="width:96%"></textarea>
			        <br/><br/>
			        
			        <label for="your_name"><?php echo _e( 'Name *', THEMEDOMAIN ); ?></label>
			        <input id="your_name" name="your_name" type="text" class="required_field" style="width:96%"/>
			        <br/><br/>
			        
			        <label for="phone"><?php echo _e( 'Phone *', THEMEDOMAIN ); ?></label>
			        <input id="phone" name="phone" type="text" class="required_field" style="width:96%"/>
			        <br/><br/>
			        
			        <label for="email"><?php echo _e( 'Email *', THEMEDOMAIN ); ?></label>
			        <input id="email" name="email" type="text" class="required_field email" style="width:96%"/>
			        <br/><br/>
			    	
			    	<br/>
			    	<p>
    					<input id="contact_submit_btn" type="submit" class="" value="<?php echo _e( 'Book', THEMEDOMAIN ); ?>"/>
			    	</p>
			    	<br/>
    			</form>
    	<!-- End main content -->
    	<br class="clear"/>
    	</div>
    	
    	<div class="sidebar_wrapper">
    	    <div class="sidebar">
    	    	
    	    	<div class="content">
    	    	
    	    		<ul class="sidebar_widget">
    	    			<?php dynamic_sidebar($page_sidebar); ?>
    	    		</ul>
    	    		
    	    	</div>
    	    
    	    </div>
    
    	    </div>
    	</div>
    </div>
</div>
<br class="clear"/><br/>
<?php get_footer(); ?>		