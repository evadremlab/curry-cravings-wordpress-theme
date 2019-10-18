<?php 
header("content-type: application/x-javascript");
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );
?>

<?php
	if(!isset($_GET['map_id']))
	{
		$_GET['map_id'] = 'map_contact';
	}
    $pp_contact_lat = get_option('pp_contact_lat');
    $pp_contact_long = get_option('pp_contact_long');
    $pp_contact_map_zoom = get_option('pp_contact_map_zoom');
    $pp_contact_map_popup = get_option('pp_contact_map_popup');
?>
    jQuery(document).ready(function(){ 
        var myOptions = {
		    zoom: <?php echo $pp_contact_map_zoom; ?>,
		    scrollwheel: false,
		    navigationControl: false,
		    mapTypeControl: false,
		    scaleControl: false,
		    draggable: false,
		    center: new google.maps.LatLng(<?php echo $pp_contact_lat; ?>, <?php echo $pp_contact_long; ?>),
		    mapTypeId: google.maps.MapTypeId.ROADMAP,
		    styles: [{"featureType":"administrative","stylers":[{"visibility":"off"}]},{"featureType":"poi","stylers":[{"visibility":"simplified"}]},{"featureType":"road","stylers":[{"visibility":"simplified"}]},{"featureType":"water","stylers":[{"visibility":"simplified"}]},{"featureType":"transit","stylers":[{"visibility":"simplified"}]},{"featureType":"landscape","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"visibility":"off"}]},{"featureType":"road.local","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"water","stylers":[{"color":"#84afa3"},{"lightness":52}]},{"stylers":[{"saturation":-77}]},{"featureType":"road"}]
		};
		
		var map = new google.maps.Map(document.getElementById('<?php echo $_GET['map_id']; ?>'), myOptions);
		
		var myLatlng = new google.maps.LatLng(<?php echo $pp_contact_lat; ?>, <?php echo $pp_contact_long; ?>);
		
		var contentString = '<?php echo esc_js($pp_contact_map_popup); ?>';
	
		var infowindow = new google.maps.InfoWindow({
		    content: contentString
		});
		
		var marker = new google.maps.Marker({
		    position: myLatlng,
		    map: map
		});
		
		google.maps.event.addListener(marker, 'click', function() {
		    infowindow.open(map,marker);
		  });
    });