<?php header("content-type: application/x-javascript"); ?>

<?php
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );
?>

<?php
if(isset($_GET['gallery_id']))
{
?>
imf.create("imageFlow", '<?php echo get_template_directory_uri(); ?>/image-flow.php?gallery_id=<?php echo $_GET['gallery_id']; ?>', 0.6, 0.2, 0, 10, 0, 0);
<?php
}
else
{
?>
imf.create("imageFlow", '<?php echo get_template_directory_uri(); ?>/image-flow.php', 0.6, 0.2, 0, 10, 0, 0);
<?php
}
?>