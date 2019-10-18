<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 */
?>
	
<?php
	/**
    *	Setup Google Analyric Code
    **/
	$pp_ga_code = get_option('pp_ga_code');
	
	if(!empty($pp_ga_code))
	{
		echo stripslashes($pp_ga_code);
	}
	
	//Check if blank template
	global $is_no_header;
	
	if(!is_bool($is_no_header) OR !$is_no_header)
	{
?>

</div>

<?php
	global $pp_contact_display_map;
    
    if(!empty($pp_contact_display_map))
    {
    	wp_enqueue_script("gmap", get_stylesheet_directory_uri()."/js/gmap.js", false, THEMEVERSION, true);
    	wp_enqueue_script("script-contact-map", get_template_directory_uri()."/templates/script-contact-map.php", false, THEMEVERSION, true);
?>
    <div class="map_shadow fullwidth">
    	<div id="map_contact"></div>
    </div>
<?php
    }
    else
    {
?>
	<br/>
<?php
    }

	global $pp_homepage_style;
?>

<div class="footer_bar <?php if(isset($pp_homepage_style) && !empty($pp_homepage_style)) { echo $pp_homepage_style; } ?>">
	<?php
	if($pp_homepage_style!='flow' && $pp_homepage_style!='fullscreen' && $pp_homepage_style!='carousel' && $pp_homepage_style!='flip' && $pp_homepage_style!='fullscreen_video')
	{
	    $pp_footer_display_sidebar = get_option('pp_footer_display_sidebar');
	
	    if(!empty($pp_footer_display_sidebar))
	    {
	    	$pp_footer_style = get_option('pp_footer_style');
	    	$footer_class = '';
	    	
	    	switch($pp_footer_style)
	    	{
	    		case 1:
	    			$footer_class = 'one';
	    		break;
	    		case 2:
	    			$footer_class = 'two';
	    		break;
	    		case 3:
	    			$footer_class = 'three';
	    		break;
	    		case 4:
	    			$footer_class = 'four';
	    		break;
	    		default:
	    			$footer_class = 'four';
	    		break;
	    	}
	    	
	    	global $pp_homepage_style;
	?>
	<div id="footer" class="<?php if(isset($pp_homepage_style) && !empty($pp_homepage_style)) { echo $pp_homepage_style; } ?>">
	<ul class="sidebar_widget <?php echo $footer_class; ?>">
	    <?php dynamic_sidebar('Footer Sidebar'); ?>
	</ul>
	
	<br class="clear"/>
	</div>
	<?php
	    }
	}
	?>

	<div class="footer_bar_wrapper <?php if(isset($pp_homepage_style) && !empty($pp_homepage_style)) { echo $pp_homepage_style; } ?>">
		<?php
			if($pp_homepage_style!='flow' && $pp_homepage_style!='fullscreen' && $pp_homepage_style!='carousel' && $pp_homepage_style!='flip' && $pp_homepage_style!='fullscreen_video')
			{	
		?>
			<?php 	
		    	if ( has_nav_menu( 'footer-menu' ) ) 
		    	{
		    	    //Get page nav
		    	    wp_nav_menu( 
		    	        	array( 
		    	        		'menu_id'			=> 'footer_menu',
		    	        		'menu_class'		=> 'footer_nav',
		    	        		'theme_location' 	=> 'footer-menu',
		    	        	) 
		    	    ); 
		    	}
		    ?>
		<?php
			}
		?>
	    <?php
	        $pp_footer_text = get_option('pp_footer_text');
	        if(!empty($pp_footer_text))
	        {
	        	echo '<div id="copyright">'.stripslashes($pp_footer_text).'</div><br class="clear"/>';
	        }
	    ?>

	    <div id="toTop"><i class="fa fa-angle-up"></i></div>
	</div>
</div>

<?php
    } //End if not blank template
?>

<div id="ajax_loading"><i class="fa fa-spinner fa-spin"></i></div>

<?php
	/**
    *	Setup code before </body>
    **/
	$pp_before_body_code = get_option('pp_before_body_code');
	
	if(!empty($pp_before_body_code))
	{
		echo stripslashes($pp_before_body_code);
	}
?>

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
