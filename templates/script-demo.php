<?php 
header("content-type: application/x-javascript"); 
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );
?>

$j(document).ready(function() {
	$j('.icheck').iCheck({
	    checkboxClass: 'icheckbox_flat-green',
	    radioClass: 'iradio_flat-green'
	});
	
	jQuery('#option_btn').click(
		function() {
			if(jQuery('#option_wrapper').css('left') != '0px')
			{	
	    		jQuery('#option_wrapper').animate({"left": "0px"}, { duration: 500 });
	 			jQuery(this).animate({"left": "250px"}, { duration: 500 });
	 		}
	 		else
	 		{
	 			var isOpenOption = jQuery.cookie("proxima_demo");
				if(jQuery.type(isOpenOption) === "undefined")
	    		{
	    			jQuery.cookie("proxima_demo", 1, { expires : 1 });
	    		}
	 			jQuery('#option_wrapper').animate({"left": "-260px"}, { duration: 500 });
				jQuery('#option_btn').animate({"left": "-2px"}, { duration: 500 });
	 		}
		}
	);
	
	var isOpenOption = jQuery.cookie("proxima_demo");
	if(jQuery.type(isOpenOption) === "undefined")
	{
	    jQuery('#option_btn').trigger('click');
	}
});