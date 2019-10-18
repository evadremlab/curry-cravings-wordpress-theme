<?php
/**
 * The main template file for display single post page.
 *
 * @package WordPress
*/

/**
*	Get current page id
**/

$current_page_id = $post->ID;

if($post->post_type=='attachment')
{
	get_template_part("single-attachment");
	exit;
}

if($post_type == 'galleries')
{
	//Get gallery template
	$gallery_template = get_post_meta($current_page_id, 'gallery_template', true);
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
}
elseif($post_type == 'portfolios')
{
	//Get portfolio content type
	$portfolio_type = get_post_meta($post->ID, 'portfolio_type', true);
	
	switch($portfolio_type)
	{
		case "Fullscreen Vimeo Video":
			get_template_part("single-portfolio-vimeo");
			exit;
		break;
		
		case "Fullscreen Youtube Video":
			get_template_part("single-portfolio-youtube");
			exit;
		break;
		
		case "Fullscreen Self-Hosted Video":
			get_template_part("single-portfolio-self-hosted");
			exit;
		break;
		
		case "Portfolio Content":
		default:
			//Get portfolio content template
			$portfolio_content_template = get_post_meta($post->ID, 'portfolio_content_template', true);
			
			if($portfolio_content_template == 'With Sidebar')
			{
				get_template_part("single-portfolio-r");
			}
			else
			{
				get_template_part("single-portfolio-f");
			}
			exit;
		break;
	}
	exit;
}
elseif($post_type == 'events')
{
	get_template_part("single-event-r");
	exit;
}
else
{
	$post_layout = get_post_meta($post->ID, 'post_layout', true);
	
	switch($post_layout)
	{
		case "With Right Sidebar":
		default:
			get_template_part("single-post-r");
			exit;
		break;
		
		case "With Left Sidebar":
			get_template_part("single-post-l");
			exit;
		break;
		
		case "Fullwidth":
			get_template_part("single-post-f");
			exit;
		break;
	}
}
?>