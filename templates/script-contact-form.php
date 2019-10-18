<?php 
header("content-type: application/x-javascript"); 
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );

$pp_contact_enable_captcha = get_option('pp_contact_enable_captcha');

//Get contact form ID
$contact_form_id = 'contact_form';
$response_id = 'reponse_msg';

if(isset($_GET['form']))
{
	$contact_form_id.= '_'.$_GET['form'];
	$response_id.= '_'.$_GET['form'];
}
?>

<?php
 if(!empty($pp_contact_enable_captcha))
 {
 ?>
 
 // refresh captcha
 $j('img#captcha-refresh').click(function() {  
     	
     	change_captcha();
 });
 
 function change_captcha()
 {
     document.getElementById('captcha').src="<?php echo get_stylesheet_directory_uri(); ?>/get_captcha.php?rnd=" + Math.random();
 }
 
 <?php
 }
?>

$j(document).ready(function() {
	$j('form#<?php echo $contact_form_id; ?>').submit(function() {
		$j('form#<?php echo $contact_form_id; ?> .error').remove();
		var hasError = false;
		$j('.required_field').each(function() {
			if(jQuery.trim($j(this).val()) == '') {
				var labelText = $j(this).prev('label').text();
				$j('#<?php echo $response_id; ?> ul').append('<li class="error"><?php echo _e( 'Please enter', THEMEDOMAIN ); ?> '+labelText+'</li>');
				hasError = true;
			} else if($j(this).hasClass('email')) {
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				if(!emailReg.test(jQuery.trim($j(this).val()))) {
					var labelText = $j(this).prev('label').text();
					$j('#<?php echo $response_id; ?> ul').append('<li class="error"><?php echo _e( 'Please enter valid', THEMEDOMAIN ); ?> '+labelText+'</li>');
					hasError = true;
				}
			}
		});
		if(!hasError) {
			var contactData = jQuery('#<?php echo $contact_form_id; ?>').serialize();

			<?php
			if(!empty($pp_contact_enable_captcha))
			{
			?>
			$j.ajax({
			    type: 'GET',
			    url: '<?php echo get_stylesheet_directory_uri(); ?>/get_captcha.php?check=true',
			    data: $j('#<?php echo $contact_form_id; ?>').serialize(),
			    success: function(msg){
			    	if(msg == 'true')
			    	{
			    		$j('#contact_submit_btn').fadeOut('normal', function() {
							$j(this).parent().append('<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/loading.gif" alt="Loading" />');
						});
						
			    		$j.ajax({
						    type: 'POST',
						    url: tgAjax.ajaxurl,
						    data: contactData+'&tg_security='+tgAjax.ajax_nonce,
						    success: function(results){
						    	$j('#<?php echo $contact_form_id; ?>').hide();
						    	$j('#<?php echo $response_id; ?>').html(results);
						    }
						});
			    	}
			    	else
			    	{
			    		alert(msg);
			    		return false;
			    	}
			    }
			});
			<?php
 			} else {
 			?>
 			$j('#contact_submit_btn').fadeOut('normal', function() {
				$j(this).parent().append('<img src="<?php echo get_template_directory_uri(); ?>/images/loading.gif" alt="Loading" />');
			});
 			
 			$j.ajax({
			    type: 'POST',
			    url: tgAjax.ajaxurl,
			    data: contactData+'&tg_security='+tgAjax.ajax_nonce,
			    success: function(results){
			    	$j('#<?php echo $contact_form_id; ?>').hide();
			    	$j('#<?php echo $response_id; ?>').html(results);
			    }
			});
 			<?php
			}
			?>
		}
		
		return false;
		
	});
});