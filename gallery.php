<?php
/**
 * Template Name: Gallery
 * The main template file for display gallery page
 *
 * @package WordPress
*/

$page_gallery_id = get_post_meta($post->ID, 'page_gallery_id', true);
$gallery_template = get_post_meta($page_gallery_id, 'gallery_template', true);
global $page_gallery_id;

switch($gallery_template)
{   
    case 'Gallery 2 Columns':
    	get_template_part("gallery-2");
    break;
    
    case 'Gallery 3 Columns':
    	get_template_part("gallery-3");
    break;
    
    case 'Gallery 4 Columns':
    	get_template_part("gallery-4");
    break;
    
    case 'Gallery Fullscreen':
    	get_template_part("gallery-f");
    break;
    
    case 'Gallery Image Flow':
    	get_template_part("gallery-flow");
    break;
    
    case 'Gallery Kenburns':
    	get_template_part("gallery-kenburns");
    break;
}

exit;
?>