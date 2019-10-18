<?php
// Disable Gutenberg
add_filter( 'gutenberg_can_edit_post_type', '__return_false' );
add_filter( 'use_block_editor_for_post_type', '__return_false' );
// Disable "Try Gutenberg" panel
remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );

function ppb_text_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'background' => '',
		'background_parallax' => '',
		'custom_css' => '',
	), $atts));

	$return_html = '<div class="'.$size.' withsmallpadding ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg ';
	}
	
	if(!empty($background_parallax))
	{
		$return_html.= 'parallax';
	}
	$return_html.= '"';
	
	$parallax_data = '';
	
	//Get image width and height
	$pp_background_image_id = pp_get_image_id($background);
	$background_image_arr = wp_get_attachment_image_src($pp_background_image_id, 'original');
	
	$background_image = $background_image_arr[0];
	$background_image_width = $background_image_arr[1];
	$background_image_height = $background_image_arr[2];

	//Check parallax background
	switch($background_parallax)
	{
		case 'scroll_pos':
			$parallax_data = 'data-image="'.$background_image.'" data-width="'.$background_image_width.'" data-height="'.$background_image_height.'"';
		break;
	}
	
	if(empty($background_parallax) && !empty($background))
	{
		$return_html.= 'style="background-image:url('.$background_image.');background-size:cover;" ';
	}
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= $parallax_data;
	$return_html.= '><div class="page_content_wrapper">'.do_shortcode(tg_apply_content($content)).'</div>';
	
	$return_html.= '</div>';

	return $return_html;

}

add_shortcode('ppb_text', 'ppb_text_func');


function ppb_divider_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one'
	), $atts));

	$return_html = '<div class="divider '.$size.'">&nbsp;</div>';

	return $return_html;

}

add_shortcode('ppb_divider', 'ppb_divider_func');


function ppb_menu_simple_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'items' => 4,
		'cat' => '',
		'order' => 'default',
		'custom_css' => '',
		'columns' => 2,
	), $atts));
	
	if(!is_numeric($items))
	{
		$items = -1;
	}
	
	if(!is_numeric($columns))
	{
		$columns = 2;
	}
	
	$return_html = '<div class="ppb_menu_simple '.$size.' withpadding ';
	
	switch($columns)
	{
		case 1:
			$columns_class = 'portfolio1';
			$columns_element = '';
			$wrapper_class = '';
			$portfolio_h = 'h5';
		break;
		
		case 2:
		default:
			$columns_class = 'portfolio2';
			$columns_element = 'element classic2_cols';
			$wrapper_class = 'two_cols';
			$portfolio_h = 'h5';
		break;
		
		case 3:
			$columns_class = 'portfolio3';
			$columns_element = 'element classic3_cols';
			$wrapper_class = 'three_cols';
			$portfolio_h = 'h5';
		break;
	}
	
	$return_html.= '" ';
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= '>';
	$return_html.='<div class="page_content_wrapper">';
	
	$menu_order = 'ASC';
	$menu_order_by = 'menu_order';
	switch($order)
	{
		case 'default':
			$menu_order = 'ASC';
			$menu_order_by = 'menu_order';
		break;
		
		case 'newest':
			$menu_order = 'DESC';
			$menu_order_by = 'post_date';
		break;
		
		case 'oldest':
			$menu_order = 'ASC';
			$menu_order_by = 'post_date';
		break;
		
		case 'title':
			$menu_order = 'ASC';
			$menu_order_by = 'title';
		break;
		
		case 'random':
			$menu_order = 'ASC';
			$menu_order_by = 'rand';
		break;
	}
	
	//Get portfolio items
	$args = array(
	    'numberposts' => $items,
	    'order' => $menu_order,
	    'orderby' => $menu_order_by,
	    'post_type' => array('menus'),
	);
	
	if(!empty($cat))
	{
		$args['menucats'] = $cat;
	}
	$menus_arr = get_posts($args);
	
	if(!empty($menus_arr) && is_array($menus_arr))
	{
		if($columns > 1)
		{
			$return_html.= '<div class="portfolio_filter_wrapper gallery '.$wrapper_class.' portfolio-content section content clearfix">';
		}
	
		foreach($menus_arr as $key => $menu)
		{
			$menu_ID = $menu->ID;
			
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
			$regular_price_label = get_post_meta($menu_ID, 'regular_price_label' );
			$alternate_price = get_post_meta($menu_ID, 'alternate_price' );
      $alternate_price_label = get_post_meta($menu_ID, 'alternate_price_label' );

			if($columns > 1)
			{
				$return_html.= '<div class="'.$columns_element.'">';
			}
			
			//Begin display HTML
			$return_html.= '<div id="portfolio_desc_'.$menu_ID.'" class="portfolio_desc '.$columns_class.' textleft">';
            $return_html.= '<'.$portfolio_h.' class="menu_post">';
            $return_html.= '<span class="menu_title">'.$menu->post_title.'</span>';
            $return_html.= '<span class="menu_dots"></span>';
            $return_html.= '<div class="menu_price" style="font-size:1rem; top:1px; padding-left:2px;">';

			if(is_front_page()) {
				$return_html.= '&nbsp;'.$menu_price_currency[0].$menu_price[0];
			}
			else
			{
				if(isset($regular_price_label[0]))
				{
				  $return_html.= '<b class="alternate-price">'.$regular_price_label[0].'</b>';
				}
				$return_html.= '&nbsp;'.$menu_price_currency[0].$menu_price[0];
				if(isset($alternate_price_label[0]) && isset($alternate_price[0]) && $alternate_price[0] > 0)
				{
          $return_html.= '<span class="alternate-price">';
				  $return_html.= '&nbsp;/&nbsp;<b>'.$alternate_price_label[0].'</b>';
          $return_html.= '&nbsp;'.$menu_price_currency[0].$alternate_price[0];
          $return_html.= '</span>';
				}
			}
			$return_html.= '</div>';

			$return_html.= '</'.$portfolio_h.'>';
            $return_html.= '<div class="post_detail menu_excerpt">'.$menu->post_excerpt.'</div>';
			
			if(!empty($menu_highlight) && !empty($menu_highlight_title))
			{
				$return_html.= '<div class="menu_highlight">'.$menu_highlight_title[0].'</div>';
			}
			
			if($columns > 1)
			{
				$return_html.= '</div>';
			}
			
			$return_html.= '</div>';
		}
		
		if($columns > 1)
		{
			$return_html.= '</div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '</div>';
	
	return $return_html;
}

add_shortcode('ppb_menu_simple', 'ppb_menu_simple_func');


function ppb_menu_card_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'items' => 4,
		'cat' => '',
		'order' => 'default',
		'custom_css' => '',
		'columns' => 2,
	), $atts));
	
	if(!is_numeric($items))
	{
		$items = -1;
	}
	
	if(!is_numeric($columns))
	{
		$columns = 2;
	}
	
	$return_html = '<div class="ppb_menu_card '.$size.' withpadding ';
	
	switch($columns)
	{
		case 2:
		default:
			$columns_class = 'one_half gallery2';
			$element_class = 'element classic2_cols';
			$portfolio_desc_class = 'portfolio2';
			$wrapper_class = 'two_cols';
			$portfolio_h = 'h5';
		break;
		
		case 3:
			$columns_class = 'one_third gallery3';
			$element_class = 'element classic3_cols';
			$portfolio_desc_class = 'portfolio3';
			$wrapper_class = 'three_cols';
			$portfolio_h = 'h5';
		break;
		
		case 4:
			$columns_class = 'one_fourth gallery4';
			$element_class = 'element classic4_cols';
			$portfolio_desc_class = 'portfolio2';
			$wrapper_class = 'four_cols';
			$portfolio_h = 'h5';
		break;
	}
	
	$return_html.= '" ';
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= '>';
	$return_html.='<div class="page_content_wrapper">';
	
	$menu_order = 'ASC';
	$menu_order_by = 'menu_order';
	switch($order)
	{
		case 'default':
			$menu_order = 'ASC';
			$menu_order_by = 'menu_order';
		break;
		
		case 'newest':
			$menu_order = 'DESC';
			$menu_order_by = 'post_date';
		break;
		
		case 'oldest':
			$menu_order = 'ASC';
			$menu_order_by = 'post_date';
		break;
		
		case 'title':
			$menu_order = 'ASC';
			$menu_order_by = 'title';
		break;
		
		case 'random':
			$menu_order = 'ASC';
			$menu_order_by = 'rand';
		break;
	}
	
	//Get portfolio items
	$args = array(
	    'numberposts' => $items,
	    'order' => $menu_order,
	    'orderby' => $menu_order_by,
	    'post_type' => array('menus'),
	);
	
	if(!empty($cat))
	{
		$args['menucats'] = $cat;
	}
	$menus_arr = get_posts($args);
	
	if(!empty($menus_arr) && is_array($menus_arr))
	{
		if($columns > 1)
		{
			$return_html.= '<div class="portfolio_filter_wrapper gallery '.$wrapper_class.' portfolio-content section content clearfix">';
		}
	
		foreach($menus_arr as $key => $menu)
		{
			$menu_ID = $menu->ID;
			
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
			
			//Get menu image
			if(has_post_thumbnail($menu_ID, 'large'))
			{
			    $image_id = get_post_thumbnail_id($menu_ID);
			    $image_url = wp_get_attachment_image_src($image_id, 'large', true);
			    
			    $small_image_url = wp_get_attachment_image_src($image_id, 'gallery_2', true);
			}
			
			//Begin display HTML
			$return_html.= '<div class="'.$element_class.'">';
			$return_html.= '<div class="'.$columns_class.' filterable gallery_type animated'.($key+1).' static">';
			
			if(!empty($image_url[0]))
			{
				$return_html.= '<a data-title="<strong>'.$menu->post_title.'</strong>" href="'.$image_url[0].'" class="fancy-gallery">';
				$return_html.= '<img src="'.$small_image_url[0].'" alt="" />';
				$return_html.= '<div class="thumb_content">';
				$return_html.= '<div id="portfolio_desc_'.$menu_ID.'" class="portfolio_desc '.$portfolio_desc_class.' textleft">';
	            $return_html.= '<'.$portfolio_h.' class="menu_post">';
	            $return_html.= '<span class="menu_title">'.$menu->post_title.'</span>';
	            $return_html.= '<span class="menu_price">'.$menu_price_currency[0].$menu_price[0].'</span>';
	            $return_html.= '</'.$portfolio_h.'>';
	            
	            if($columns!=4)
	            {
		            $return_html.= '<div class="post_detail menu_excerpt">'.$menu->post_excerpt.'</div>';
					
					if(!empty($menu_highlight) && !empty($menu_highlight_title))
					{
						$return_html.= '<div class="menu_highlight">'.$menu_highlight_title[0].'</div>';
					}
				}
				
				$return_html.= '</div>';
				$return_html.= '</div>';
				$return_html.= '</a>';
			}
			$return_html.= '</div>';
			$return_html.= '</div>';
		}
		
		if($columns > 1)
		{
			$return_html.= '</div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '</div>';
	
	return $return_html;
}

add_shortcode('ppb_menu_card', 'ppb_menu_card_func');


function ppb_menu_classic_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'items' => 4,
		'cat' => '',
		'order' => 'default',
		'custom_css' => '',
		'columns' => 2,
	), $atts));
	
	if(!is_numeric($items))
	{
		$items = -1;
	}
	
	if(!is_numeric($columns))
	{
		$columns = 2;
	}
	
	$return_html = '<div class="ppb_menu_classic '.$size.' withpadding ';
	
	switch($columns)
	{
		case 2:
		default:
			$columns_class = 'one_half gallery2 static';
			$element_class = 'element classic2_cols';
			$portfolio_desc_class = 'portfolio2';
			$wrapper_class = 'two_cols';
			$portfolio_h = 'h5';
		break;
		
		case 3:
			$columns_class = 'one_third gallery3 static';
			$element_class = 'element classic3_cols';
			$portfolio_desc_class = 'portfolio3';
			$wrapper_class = 'three_cols';
			$portfolio_h = 'h5';
		break;
		
		case 4:
			$columns_class = 'one_fourth gallery4 static';
			$element_class = 'element classic4_cols';
			$portfolio_desc_class = 'portfolio2';
			$wrapper_class = 'four_cols';
			$portfolio_h = 'h5';
		break;
	}
	
	$return_html.= '" ';
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= '>';
	$return_html.='<div class="page_content_wrapper">';
	
	$menu_order = 'ASC';
	$menu_order_by = 'menu_order';
	switch($order)
	{
		case 'default':
			$menu_order = 'ASC';
			$menu_order_by = 'menu_order';
		break;
		
		case 'newest':
			$menu_order = 'DESC';
			$menu_order_by = 'post_date';
		break;
		
		case 'oldest':
			$menu_order = 'ASC';
			$menu_order_by = 'post_date';
		break;
		
		case 'title':
			$menu_order = 'ASC';
			$menu_order_by = 'title';
		break;
		
		case 'random':
			$menu_order = 'ASC';
			$menu_order_by = 'rand';
		break;
	}
	
	//Get portfolio items
	$args = array(
	    'numberposts' => $items,
	    'order' => $menu_order,
	    'orderby' => $menu_order_by,
	    'post_type' => array('menus'),
	);
	
	if(!empty($cat))
	{
		$args['menucats'] = $cat;
	}
	$menus_arr = get_posts($args);
	
	if(!empty($menus_arr) && is_array($menus_arr))
	{
		if($columns > 1)
		{
			$return_html.= '<div class="portfolio_filter_wrapper gallery '.$wrapper_class.' portfolio-content section content clearfix">';
		}
	
		foreach($menus_arr as $key => $menu)
		{
			$menu_ID = $menu->ID;
			
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
			
			//Get menu image
			if(has_post_thumbnail($menu_ID, 'large'))
			{
			    $image_id = get_post_thumbnail_id($menu_ID);
			    $image_url = wp_get_attachment_image_src($image_id, 'large', true);
			    
			    $small_image_url = wp_get_attachment_image_src($image_id, 'gallery_2', true);
			}
			
			//Begin display HTML
			$return_html.= '<div class="'.$element_class.'">';
			$return_html.= '<div class="'.$columns_class.' filterable gallery_type animated'.($key+1).' static">';
			
			if(!empty($image_url[0]))
			{
				$return_html.= '<a data-title="<strong>'.$menu->post_title.'</strong>" href="'.$image_url[0].'" class="fancy-gallery">';
				$return_html.= '<img src="'.$small_image_url[0].'" alt="" />';
				$return_html.= '<div class="mask">';
	            $return_html.= '<div class="mask_circle">';
				$return_html.= '<i class="fa fa-expand"/></i>';
				$return_html.= '</div>';
		        $return_html.= '</div>';
		        $return_html.= '</a>';
			}
			
			$return_html.= '</div>';
			$return_html.= '<br class="clear"/><br/>';
			$return_html.= '<div id="portfolio_desc_'.$menu_ID.'" class="portfolio_desc '.$portfolio_desc_class.' textleft">';
	        $return_html.= '<'.$portfolio_h.' class="menu_post">';
	        $return_html.= '<span class="menu_title">'.$menu->post_title.'</span>';
	        $return_html.= '<span class="menu_price">'.$menu_price_currency[0].$menu_price[0].'</span>';
	        $return_html.= '</'.$portfolio_h.'>';
	        
	        $return_html.= '<div class="post_detail menu_excerpt">'.$menu->post_excerpt.'</div>';
			    
			if(!empty($menu_highlight) && !empty($menu_highlight_title))
			{
			    $return_html.= '<div class="menu_highlight">'.$menu_highlight_title[0].'</div>';
			}
			
			$return_html.= '</div>';
			
			$return_html.= '</div>';
		}
		
		if($columns > 1)
		{
			$return_html.= '</div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '</div>';
	
	return $return_html;
}

add_shortcode('ppb_menu_classic', 'ppb_menu_classic_func');


function ppb_portfolio_grid_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'items' => 4,
		'set' => '',
		'order' => 'default',
		'custom_css' => '',
		'columns' => 3,
		'layout' => 'fullwidth',
	), $atts));
	
	if(!is_numeric($items))
	{
		$items = 4;
	}
	
	$return_html = '<div class="ppb_portfolio '.$size.' withpadding ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg';
	}
	
	switch($columns)
	{
		case 3:
			$columns_class = 'three_cols';
			$element_class = 'one_third gallery3';
			$portfolio_h = 'h3';
		break;
		
		case 4:
		default:
			$columns_class = 'four_cols';
			$element_class = 'one_fourth gallery4';
			$portfolio_h = 'h3';
		break;
	}
	
	if(empty($content) && empty($title))
	{
		$return_html.='nopadding ';
	}
	
	$return_html.= '" ';
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= '>';
	
	$return_html.='<div class="page_content_wrapper ';
	
	if($layout == 'fullwidth')
	{
		$return_html.='full_width';
	}
	
	$return_html.= '" style="text-align:center">';

	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content) && !empty($title))
	{
		$return_html.= '<br/><br/>';
	}
	
	$portfolio_order = 'ASC';
	$portfolio_order_by = 'menu_order';
	switch($order)
	{
		case 'default':
			$portfolio_order = 'ASC';
			$portfolio_order_by = 'menu_order';
		break;
		
		case 'newest':
			$portfolio_order = 'DESC';
			$portfolio_order_by = 'post_date';
		break;
		
		case 'oldest':
			$portfolio_order = 'ASC';
			$portfolio_order_by = 'post_date';
		break;
		
		case 'title':
			$portfolio_order = 'ASC';
			$portfolio_order_by = 'title';
		break;
		
		case 'random':
			$portfolio_order = 'ASC';
			$portfolio_order_by = 'rand';
		break;
	}
	
	//Get portfolio items
	$args = array(
	    'numberposts' => $items,
	    'order' => $portfolio_order,
	    'orderby' => $portfolio_order_by,
	    'post_type' => array('portfolios'),
	);
	
	if(!empty($set))
	{
		$args['portfoliosets'] = $set;
	}
	$portfolios_arr = get_posts($args);
	
	if(!empty($portfolios_arr) && is_array($portfolios_arr))
	{
		$return_html.= '<div id="portfolio_filter_wrapper" class="shortcode '.$columns_class.' gallery portfolio-content section content clearfix">';
	
		foreach($portfolios_arr as $key => $portfolio)
		{
			$image_url = '';
			$portfolio_ID = $portfolio->ID;
					
			if(has_post_thumbnail($portfolio_ID, 'large'))
			{
			    $image_id = get_post_thumbnail_id($portfolio_ID);
			    $image_url = wp_get_attachment_image_src($image_id, 'full', true);
			    
			    $small_image_url = wp_get_attachment_image_src($image_id, 'gallery_grid', true);
			}
			
			$portfolio_link_url = get_post_meta($portfolio_ID, 'portfolio_link_url', true);
			
			if(empty($portfolio_link_url))
			{
			    $permalink_url = get_permalink($portfolio_ID);
			}
			else
			{
			    $permalink_url = $portfolio_link_url;
			}
			
			$last_class = '';
			if(($key+1)%4==0)
			{
				$last_class = 'last';
			}
			
			//Begin display HTML
			$return_html.= '<div class="element portfolio3filter_wrapper">';
			$return_html.= '<div class="'.$element_class.' filterable animated'.($key+1).' '.$last_class.'">';
			
			if(!empty($image_url[0]))
			{
				$portfolio_type = get_post_meta($portfolio_ID, 'portfolio_type', true);
			    $portfolio_video_id = get_post_meta($portfolio_ID, 'portfolio_video_id', true);
			    
			    switch($portfolio_type)
			    {
			    case 'External Link':
					$portfolio_link_url = get_post_meta($portfolio_ID, 'portfolio_link_url', true);
			
					$return_html.= '<a target="_blank" href="'.$portfolio_link_url.'">';
					$return_html.= '<img src="'.$small_image_url[0].'" alt=""/>';

		            $return_html.= '<div class="thumb_content">';
		            $return_html.= '<'.$portfolio_h.'>'.$portfolio->post_title.'</'.$portfolio_h.'>';
					$return_html.= '<span>'.$portfolio->post_excerpt.'</span>';
			        $return_html.= '</div>';
			        $return_html.= '</a>';
			        
			    break;
			    //end external link
			    
			    case 'Portfolio Content':
        	    default:

					$return_html.= '<a href="'.get_permalink($portfolio_ID).'">';
		        	$return_html.= '<img src="'.$small_image_url[0].'" alt=""/>';
					
		            $return_html.= '<div class="thumb_content">';
		            $return_html.= '<'.$portfolio_h.'>'.$portfolio->post_title.'</'.$portfolio_h.'>';
					$return_html.= '<span>'.$portfolio->post_excerpt.'</span>';
			        $return_html.= '</div>';
			        $return_html.= '</a>';
	        
			    break;
			    //end external link
			    
			    case 'Fullscreen Vimeo Video':
        	    case 'Fullscreen Youtube Video':
        	    case 'Fullscreen Self-Hosted Video':

					$return_html.= '<a href="'.get_permalink($portfolio_ID).'">';
		        	$return_html.= '<img src="'.$small_image_url[0].'" alt=""/>';
					
		            $return_html.= '<div class="thumb_content">';
		            $return_html.= '<'.$portfolio_h.'>'.$portfolio->post_title.'</'.$portfolio_h.'>';
					$return_html.= '<span>'.$portfolio->post_excerpt.'</span>';
			        $return_html.= '</div>';
			        $return_html.= '</a>';
	        
        	    break;
        	    //end fullscreen video Content
        	    
        	    case 'Image':
			
					$return_html.= '<a data-title="<strong>'.$portfolio->post_title.'</strong>'.remove_shortcode($portfolio->post_content).'" href="'.$image_url[0].'" class="fancy-gallery">';
					$return_html.= '<img src="'.$small_image_url[0].'" alt=""/>';
					
		            $return_html.= '<div class="thumb_content">';
		            $return_html.= '<'.$portfolio_h.'>'.$portfolio->post_title.'</'.$portfolio_h.'>';
					$return_html.= '<span>'.$portfolio->post_excerpt.'</span>';
			        $return_html.= '</div>';
			        $return_html.= '</a>';
			
			    break;
			    //end image
			    
			    case 'Youtube Video':
			
					$return_html.= '<a title="'.$portfolio->post_title.'" href="#video_'.$portfolio_video_id.'" class="lightbox_youtube">';
					$return_html.= '<img src="'.$small_image_url[0].'" alt=""/>';
					
		            $return_html.= '<div class="thumb_content">';
		            $return_html.= '<'.$portfolio_h.'>'.$portfolio->post_title.'</'.$portfolio_h.'>';
					$return_html.= '<span>'.$portfolio->post_excerpt.'</span>';
			        $return_html.= '</div>';
			        $return_html.= '</a>';
					    
					$return_html.= '<div style="display:none;">
					    <div id="video_'.$portfolio_video_id.'" style="width:900px;height:488px">
					        
					        <iframe title="YouTube video player" width="900" height="488" src="http://www.youtube.com/embed/'.$portfolio_video_id.'?theme=dark&amp;rel=0&amp;wmode=transparent" allowfullscreen></iframe>
					        
					    </div>	
					</div>';
			
			    break;
			    //end youtube
			
			case 'Vimeo Video':

					$return_html.= '<a title="'.$portfolio->post_title.'" href="#video_'.$portfolio_video_id.'" class="lightbox_vimeo">';
					$return_html.= '<img src="'.$small_image_url[0].'" alt=""/>';
		
		            $return_html.= '<div class="thumb_content">';
		            $return_html.= '<'.$portfolio_h.'>'.$portfolio->post_title.'</'.$portfolio_h.'>';
					$return_html.= '<span>'.$portfolio->post_excerpt.'</span>';
			        $return_html.= '</div>';
			        $return_html.= '</a>';
					    
					$return_html.= '<div style="display:none;">
					    <div id="video_'.$portfolio_video_id.'" style="width:900px;height:506px">
					    
					        <iframe src="http://player.vimeo.com/video/'.$portfolio_video_id.'?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="900" height="506"></iframe>
					        
					    </div>	
					</div>';
			
			    break;
			    //end vimeo
			    
			case 'Self-Hosted Video':
			
				    //Get video URL
				    $portfolio_mp4_url = get_post_meta($portfolio_ID, 'portfolio_mp4_url', true);
				    $preview_image = wp_get_attachment_image_src($image_id, 'large', true);
			    
			    	$return_html.= '<a title="'.$portfolio->post_title.'" href="#video_self_'.$portfolio_video_id.'" class="lightbox_vimeo">';
					$return_html.= '<img src="'.$small_image_url[0].'" alt=""/>';
		
					$return_html.= '<div class="thumb_content">';
		            $return_html.= '<'.$portfolio_h.'>'.$portfolio->post_title.'</'.$portfolio_h.'>';
					$return_html.= '<span>'.$portfolio->post_excerpt.'</span>';
			        $return_html.= '</div>';
			        $return_html.= '</a>';
					    
					$return_html.= '<div style="display:none;">
					    <div id="video_self_'.$key.'" style="width:900px;height:488px">
					    
					        <div id="self_hosted_vid_'.$key.'"></div>
					        '.do_shortcode('[jwplayer id="self_hosted_vid_'.$key.'" file="'.$portfolio_mp4_url.'" image="'.$preview_image[0].'" width="900" height="488"]').'
					        
					    </div>	
					</div>';
			
			    break;
			    //end self-hosted
			    }
			    //end switch
			}
			$return_html.= '</div>';
			$return_html.= '</div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '</div></div>';
	
	return $return_html;
}

add_shortcode('ppb_portfolio_grid', 'ppb_portfolio_grid_func');


function ppb_gallery_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'gallery' => '',
		'background' => '',
		'custom_css' => '',
		'columns' => 3,
	), $atts));
	
	$return_html = '<div class="'.$size.' ppb_gallery withpadding ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg';
	}
	
	switch($columns)
	{
		case 3:
			$columns_class = 'three_cols';
			$element_class = 'one_third gallery3';
		break;
		
		case 4:
		default:
			$columns_class = 'four_cols';
			$element_class = 'one_fourth gallery4';
		break;
	}
	
	$return_html.= '" ';
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).' ';
	}
	
	if(!empty($background))
	{
		if(!empty($custom_css))
		{
			$return_html.= 'background-image: url('.$background.');background-attachment: fixed;background-position: center top;background-repeat: no-repeat;background-size: cover;" ';
		}
		else
		{
			$return_html.= 'style="background-image: url('.$background.');background-attachment: fixed;background-position: center top;background-repeat: no-repeat;background-size: cover;" ';
		}
		
		$return_html.= 'data-type="background" data-speed="10"';
	}
	else
	{
		$return_html.= '"';
	}
	
	$return_html.= '>';
	
	$return_html.='<div class="page_content_wrapper ';
	
	$return_html.= '" style="text-align:center">';

	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content) && !empty($title))
	{
		$return_html.= '<br/><br/>';
	}
	
	//Get gallery images
	$all_photo_arr = get_post_meta($gallery, 'wpsimplegallery_gallery', true);
	
	//Get global gallery sorting
	$all_photo_arr = pp_resort_gallery_img($all_photo_arr);

	if(!empty($all_photo_arr) && is_array($all_photo_arr))
	{
		$return_html.= '<div id="portfolio_filter_wrapper" class="shortcode '.$columns_class.' gallery portfolio-content section content clearfix">';
		
		foreach($all_photo_arr as $key => $photo_id)
		{
		    $small_image_url = '';
		    $hyperlink_url = get_permalink($photo_id);
		    
		    if(!empty($photo_id))
		    {
		    	$image_url = wp_get_attachment_image_src($photo_id, 'original', true);
		        $small_image_url = wp_get_attachment_image_src($photo_id, 'gallery_grid', true);
		    }
		    
		    $last_class = '';
		    if(($key+1)%4==0)
		    {
		    	$last_class = 'last';
		    }
		    
		    //Get image meta data
		    $image_title = get_the_title($photo_id);
		    $image_desc = get_post_field('post_content', $photo_id);
		    $image_caption = get_post_field('post_excerpt', $photo_id);
		    
		    $return_html.= '<div class="element classic3_cols">';
			$return_html.= '<div class="'.$element_class.' filterable filterable animated'.($key+1).' '.$last_class.'">';
			
			if(!empty($small_image_url[0]))
			{
	    		$pp_portfolio_enable_slideshow_title = get_option('pp_portfolio_enable_slideshow_title');
	    		$pp_social_sharing = get_option('pp_social_sharing');

				$return_html.= '<a ';
				if(!empty($pp_portfolio_enable_slideshow_title)) 
				{ 
					$return_html.= 'data-title="<strong>'.htmlentities($image_title).'</strong> ';
					if(!empty($image_desc)) 
					{ 
						$return_html.= htmlentities($image_desc); 
					} 
					if(!empty($pp_social_sharing)) 
					{ 
						$return_html.= htmlentities('<br/><br/><br/><br/><a class=\'button\' href=\''.get_permalink($photo_id).'\'>'.__( 'Comment & share', THEMEDOMAIN ).'</a>');
					} 
					$return_html.= '"';
				}
				
				$return_html.= 'class="fancy-gallery" data-fancybox-group="fancybox-thumb" href="'.$image_url[0].'" ';
				
				//Get image lightbox image title and share option
				$pp_lightbox_enable_title = get_option('pp_lightbox_enable_title');
				$pp_lightbox_enable_comment_share = get_option('pp_lightbox_enable_comment_share');
				$pp_social_sharing = get_option('pp_social_sharing');
				
				if(!empty($pp_lightbox_enable_title))
				{
					$return_html.= 'data-title="<strong>'.$image_title.'</strong>';
					
					if(!empty($image_desc))
					{
						$return_html.= htmlentities($image_desc);
					}
					
					if(!empty($pp_social_sharing) && !empty($pp_lightbox_enable_comment_share)) 
					{
						$return_html.= '<br/><br/><br/><br/><a class="button" href="'.get_permalink($photo_id).'">'.__( 'Comment & share', THEMEDOMAIN ).'</a>';
					}
					
					$return_html.= '"';
				}
				
    			$return_html.= '><img src="'.$small_image_url[0].'" alt="" class=""/></a>';
			}		
			$return_html.= '</div></div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '</div></div>';
	
	return $return_html;
}

add_shortcode('ppb_gallery', 'ppb_gallery_func');


function ppb_blog_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'category' => '',
		'items' => '',
		'background' => '',
		'background_parallax' => 'none',
		'custom_css' => '',
	), $atts));
	
	$return_html = '<div class="'.$size.' withpadding ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg ';
	}
	
	$return_html.= '" ';
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= '>';
	
	$return_html.='<div class="page_content_wrapper" style="text-align:center">';
	
	if(!is_numeric($items))
	{
		$items = 3;
	}
	
	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content))
	{
		$return_html.= '<br/><br/>';
	}
	
	//Get blog posts
	$args = array(
	    'numberposts' => $items,
	    'order' => 'DESC',
	    'orderby' => 'post_date',
	    'post_type' => array('post'),
	);

	if(!empty($category))
	{
		$args['category'] = $category;
	}
	$posts_arr = get_posts($args);
	
	if(!empty($posts_arr) && is_array($posts_arr))
	{
		$return_html.= '<div id="blog_grid_wrapper" class="sidebar_content full_width ppb_blog_posts" style="text-align:left">';
	
		foreach($posts_arr as $key => $ppb_post)
		{
			$animate_layer = $key+7;
			$image_thumb = '';
										
			if(has_post_thumbnail($ppb_post->ID, 'large'))
			{
			    $image_id = get_post_thumbnail_id($ppb_post->ID);
			    $image_thumb = wp_get_attachment_image_src($image_id, 'large', true);
			}
			
			$return_html.= '<div id="post-'.$ppb_post->ID.'" class="post type-post hentry status-publish">
			<div class="post_wrapper grid_layout">';
			
			 //Get post featured content
		    $post_ft_type = get_post_meta(get_the_ID(), 'post_ft_type', true);
		    
		    switch($post_ft_type)
		    {
		    	case 'Image':
		    	default:
		        	if(!empty($image_thumb))
		        	{
		        		$small_image_url = wp_get_attachment_image_src($image_id, 'gallery_2', true);
		
		    	    $return_html.= '<div class="post_img grid">
		    	    	<a href="'.get_permalink($ppb_post->ID).'">
		    	    		<img src="'.$small_image_url[0].'" alt="" class=""/>
		        	    	<div class="mask">
		                    	<div class="mask_circle grid">
		    	                    <i class="fa fa-external-link"/></i>
		    					</div>
		                    </div>
		                </a>
		    	    </div>';
		    		}
		    	break;
		    	
		    	case 'Vimeo Video':
		    		$post_ft_vimeo = get_post_meta($ppb_post->ID, 'post_ft_vimeo', true);
		
					$return_html.= do_shortcode('[tg_vimeo video_id="'.$post_ft_vimeo.'" width="670" height="377"]').'<br/>';
		    	break;
		    	
		    	case 'Youtube Video':
		    		$post_ft_youtube = get_post_meta($ppb_post->ID, 'post_ft_youtube', true);

		    		$return_html.= do_shortcode('[tg_youtube video_id="'.$post_ft_youtube.'" width="670" height="377"]').'<br/>';
		    	break;
		    	
		    	case 'Gallery':
		    		$post_ft_gallery = get_post_meta($ppb_post->ID, 'post_ft_gallery', true);
		    		$return_html.= do_shortcode('[tg_gallery_slider gallery_id="'.$post_ft_gallery.'" size="gallery_2" width="670" height="270"]');
		    	break;
		    	
		    } //End switch
		    
		    $return_html.= '<div class="blog_grid_content">';
		    
		    //Check post format
		    $post_format = get_post_format($ppb_post->ID);
		    
		    $write_comments = '';
		    $num_comments = get_comments_number($ppb_post->ID);
		    
		    if ( comments_open($ppb_post->ID) ) {
				if ( $num_comments == 0 ) {
					$comments = __('No Comments', THEMEDOMAIN);
				} elseif ( $num_comments > 1 ) {
					$comments = $num_comments . __(' Comments', THEMEDOMAIN);
				} else {
					$comments = __('1 Comment', THEMEDOMAIN);
				}
				$write_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
			} else {
				$write_comments =  __('Comments are off for this post.', THEMEDOMAIN);
			}
		    
		    switch($post_format)
			{
			    case 'quote':
			
			    $return_html.= '
			    
			    <div class="post_header grid">
			    	<h6>
			    		<a href="'.get_permalink($ppb_post->ID).'">'.$ppb_post->post_content.'</a>
			    	</h6>
			    		<div class="post_detail">
			    	    	'.get_the_time(THEMEDATEFORMAT, $ppb_post->ID).'&nbsp;';
			    		    
				$return_html.= '/&nbsp;'.$write_comments.'
			    		</div>
			    </div>';
			    
			    break;
			    
			    case 'link':
			
			    $return_html.= '
			    
			    <div class="post_header grid">
			    	<h6>'.$ppb_post->post_content.'</h6>
			    		
			    		<div class="post_detail">
			    	    	'.get_the_time(THEMEDATEFORMAT, $ppb_post->ID).'&nbsp;';
			    		    
				$return_html.= '/&nbsp;'.$write_comments.'
			    		</div>
			    </div>';

			    break;
			    
			    default:
		    
				$return_html.= '<div class="post_header grid">
				    <h6><a href="'.get_permalink($ppb_post->ID).'" title="'.get_the_title($ppb_post->ID).'">'.get_the_title($ppb_post->ID).'</a></h6>
				    <div class="post_detail">
			    	    '.get_the_time(THEMEDATEFORMAT, $ppb_post->ID).'&nbsp;';
			    	    
				$return_html.= '/&nbsp;'.$write_comments.'
			    	</div>
				</div>
				
				<a class="readmore grid" href="'.get_permalink($ppb_post->ID).'">'.__( 'Read More', THEMEDOMAIN ).'</a>';
		        break;
		    }
		    
		    $return_html.= '
	    </div>    
	</div>
</div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '<br class="clear"/></div></div>';
	
	return $return_html;
}

add_shortcode('ppb_blog', 'ppb_blog_func');


function ppb_transparent_video_bg_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'height' => '300',
		'description' => '',
		'mp4_video_url' => '',
		'webm_video_url' => '',
		'preview_img' => '',
	), $atts));
	
	if(!is_numeric($height))
	{
		$height = 300;
	}
	
	$return_html = '';
	$return_html.= '<div class="'.$size.' ppb_transparent_video_bg" style="position:relative;height:'.$height.'px;max-height:'.$height.'px;" >';
	$return_html.= '<div class="ppb_video_bg_mask"></div>';
	
	if(!empty($title))
	{
		$return_html.= '<div class="post_title entry_post">';
		
		if(!empty($title))
		{
			$return_html.= '<h3>'.$title.'</h3>';
		}
		
		if(!empty($description))
		{
			$return_html.= '<div class="content">'.urldecode($description).'</div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '<div style="position:relative;width:100%;height:100%;overflow:hidden">';
	
	if(!empty($mp4_video_url) OR !empty($webm_video_url))
	{
		//Generate unique ID
		$wrapper_id = 'ppb_video_'.uniqid();
		
		$return_html.= '<video ';
		
		if(!empty($preview_img))
		{
			$return_html.= 'poster="'.$preview_img.'"';
		}
		
		$return_html.= 'id="'.$wrapper_id.'" loop="true" autoplay="true" muted="muted" controls="controls">';
		
		if(!empty($mp4_video_url))
		{
			$return_html.= '<source type="video/mp4" src="'.$mp4_video_url.'" />';
		}
		
		if(!empty($webm_video_url))
		{
			$return_html.= '<source type="video/webm" src="'.$webm_video_url.'" />';
		}
		
		$return_html.= '</video>';
		
		wp_enqueue_style("mediaelementplayer", get_template_directory_uri()."/js/mediaelement/mediaelementplayer.css", false, THEMEVERSION, "all");
		wp_enqueue_script("mediaelement-and-player.min", get_template_directory_uri()."/js/mediaelement/mediaelement-and-player.min.js", false, THEMEVERSION);
		wp_enqueue_script("script-ppb-video-bg".$wrapper_id, get_stylesheet_directory_uri()."/templates/script-ppb-video-bg.php?video_id=".$wrapper_id."&height=".$height, false, THEMEVERSION, true);
	}

	$return_html.= '</div>';

	$return_html.= '</div>';

	return $return_html;

}

add_shortcode('ppb_transparent_video_bg', 'ppb_transparent_video_bg_func');


function ppb_fullwidth_button_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'link_url' => '',
	), $atts));
	
	$return_html = '<div class="'.$size.'"><a href="'.$link_url.'" class="button fullwidth ppb"><i class="fa fa-link"></i>'.$title.'</a></div>';

	return $return_html;

}

add_shortcode('ppb_fullwidth_button', 'ppb_fullwidth_button_func');


function ppb_map_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'height' => '',
	), $atts));
	
	//Get map random ID
	$map_id = time().rand();
	
	if(!is_numeric($height))
	{
		$height = 400;
	}
	
	//Add map scripts
	wp_enqueue_script("gmap", get_stylesheet_directory_uri()."/js/gmap.js", false, THEMEVERSION, true);
    wp_enqueue_script("script-contact-map", get_template_directory_uri()."/templates/script-contact-map.php?map_id=".$map_id, false, THEMEVERSION, true);
	
	$return_html = '<div class="ppb_map map_shadow fullwidth">
    	<div id="'.$map_id.'" style="height:'.$height.'px"></div>
    </div>';

	return $return_html;

}

add_shortcode('ppb_map', 'ppb_map_func');


function ppb_team_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'columns' => 3,
		'title' => '',
		'items' => 4,
		'cat' => '',
		'order' => 'default',
		'background' => '',
		'background_parallax' => 'none',
		'custom_css' => '',
	), $atts));
	
	if(!is_numeric($items))
	{
		$items = 4;
	}
	
	$return_html = '<div class="'.$size.' withpadding ppb_team ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg ';
	}
	
	if(!empty($background_parallax))
	{
		$return_html.= 'parallax';
	}
	
	$return_html.= '" ';
	
	$parallax_data = '';
	
	//Get image width and height
	$pp_background_image_id = pp_get_image_id($background);
	$background_image_arr = wp_get_attachment_image_src($pp_background_image_id, 'original');
	
	$background_image = $background_image_arr[0];
	$background_image_width = $background_image_arr[1];
	$background_image_height = $background_image_arr[2];

	//Check parallax background
	switch($background_parallax)
	{
		case 'scroll_pos':
			$parallax_data = 'data-image="'.$background_image.'" data-width="'.$background_image_width.'" data-height="'.$background_image_height.'"';
		break;
	}
	
	if(empty($background_parallax) && !empty($background))
	{
		$return_html.= 'style="background-image:url('.$background_image.');background-size:cover;" ';
	}
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= $parallax_data;
	
	$return_html.= '>';
	
	$return_html.='<div class="page_content_wrapper" style="text-align:center">';
	
	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.stripcslashes($title).'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	$portfolio_order = 'ASC';
	$portfolio_order_by = 'menu_order';
	switch($order)
	{
		case 'default':
			$portfolio_order = 'ASC';
			$portfolio_order_by = 'menu_order';
		break;
		
		case 'newest':
			$portfolio_order = 'DESC';
			$portfolio_order_by = 'post_date';
		break;
		
		case 'oldest':
			$portfolio_order = 'ASC';
			$portfolio_order_by = 'post_date';
		break;
		
		case 'title':
			$portfolio_order = 'ASC';
			$portfolio_order_by = 'title';
		break;
		
		case 'random':
			$portfolio_order = 'ASC';
			$portfolio_order_by = 'rand';
		break;
	}
	
	//Check display columns
	$count_column = 3;
	$columns_class = 'one_third';
	
	switch($columns)
	{	
		case 2:
			$count_column = 2;
			$columns_class = 'one_half';
		break;
		
		case 3:
		default:
			$count_column = 3;
			$columns_class = 'one_third';
		break;
		
		case 4:
			$count_column = 4;
			$columns_class = 'one_fourth';
		break;
	}
	
	//Get portfolio items
	$args = array(
	    'numberposts' => $items,
	    'order' => $portfolio_order,
	    'orderby' => $portfolio_order_by,
	    'post_type' => array('team'),
	);
	
	if(!empty($cat))
	{
		$args['teamcats'] = $cat;
	}
	$team_arr = get_posts($args);
	
	if(!empty($team_arr) && is_array($team_arr))
	{
		$return_html.= '<div class="team_wrapper">';
	
		foreach($team_arr as $key => $member)
		{
			$image_url = '';
			$member_ID = $member->ID;
					
			if(has_post_thumbnail($member_ID, 'team_member'))
			{
			    $image_id = get_post_thumbnail_id($member_ID);
			    $small_image_url = wp_get_attachment_image_src($image_id, 'team_member', true);
			}
			
			$last_class = '';
			if(($key+1)%$count_column==0)
			{
				$last_class = 'last';
			}
			
			//Begin display HTML
			$return_html.= '<div class="'.$columns_class.' animated'.($key+1).' '.$last_class.'">';
			
			if(!empty($small_image_url[0]))
			{
				$return_html.= '<div class="post_img large animate ';
				
				$member_facebook = get_post_meta($member_ID, 'member_facebook', true);
			    $member_twitter = get_post_meta($member_ID, 'member_twitter', true);
			    $member_google = get_post_meta($member_ID, 'member_google', true);
			    $member_linkedin = get_post_meta($member_ID, 'member_linkedin', true);
				
				if(empty($member_facebook) && empty($member_twitter) && empty($member_google) && empty($member_linkedin))
				{
					$return_html.= 'static';
				}
				
				$return_html.='" style="margin-bottom:10px"><img class="team_pic" src="'.$small_image_url[0].'" alt=""/>';
				
				if(!empty($member_facebook) OR !empty($member_twitter) OR !empty($member_google) OR !empty($member_linkedin))
				{
					$return_html.= '<div class="thumb_content shortcode">';
					$return_html.= '<div class="social_follow">'.__( 'Follow', THEMEDOMAIN ).'</div><ul class="social_wrapper team">';
					
					if(!empty($member_twitter))
					{
					    $return_html.= '<li><a title="'.$member->post_title.' on Twitter" target="_blank" class="tooltip" href="http://twitter.com/'.$member_twitter.'"><i class="fa fa-twitter"></i></a></li>';
					}
	 
					if(!empty($member_facebook))
					{
					    $return_html.= '<li><a title="'.$member->post_title.' on Facebook" target="_blank" class="tooltip" href="http://facebook.com/'.$member_facebook.'"><i class="fa fa-facebook"></i></a></li>';
					}
					
					if(!empty($member_google))
					{
					    $return_html.= '<li><a title="'.$member->post_title.' on Google+" target="_blank" class="tooltip" href="'.$member_google.'"><i class="fa fa-google-plus"></i></a></li>';
					}
					    
					if(!empty($member_linkedin))
					{
					    $return_html.= '<li><a title="'.$member->post_title.' on Linkedin" target="_blank" class="tooltip" href="'.$member_linkedin.'"><i class="fa fa-linkedin"></i></a></li>';
					}
					
					$return_html.= '</ul>';
					$return_html.= '</div>';
				}
				
				$return_html.= '</div>';
			    
			}
			
			$team_position = get_post_meta($member_ID, 'team_position', true);
			
			//Display portfolio detail
			$return_html.= '<div id="portfolio_desc_'.$member_ID.'" class="portfolio_desc team shortcode '.$last_class.'">';
            $return_html.= '<h5>'.$member->post_title.'</h5>';
            if(!empty($team_position))
            {
            	$return_html.= '<span class="portfolio_excerpt">'.$team_position.'</span>';
            }
            if(!empty($member->post_content))
            {
            	$return_html.= '<p>'.$member->post_content.'</p>';
            }
			$return_html.= '</div>';
			$return_html.= '</div>';
			
			if(($key+1)%$count_column==0)
			{
				$return_html.= '<br class="clear"/>';
			}
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '</div></div>';
	
	return $return_html;
}

add_shortcode('ppb_team', 'ppb_team_func');


function ppb_testimonial_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'title' => '',
		'items' => '',
		'cat' => '',
		'background' => '',
		'background_parallax' => 'none',
		'custom_css' => '',
	), $atts));
	
	$return_html = '<div class="one withsmallpadding ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg ';
	}
	
	if(!empty($background_parallax))
	{
		$return_html.= 'parallax';
	}
	
	$return_html.= '" ';
	
	$parallax_data = '';
	
	//Get image width and height
	$pp_background_image_id = pp_get_image_id($background);
	$background_image_arr = wp_get_attachment_image_src($pp_background_image_id, 'original');
	
	$background_image = $background_image_arr[0];
	$background_image_width = $background_image_arr[1];
	$background_image_height = $background_image_arr[2];

	//Check parallax background
	switch($background_parallax)
	{
		case 'scroll_pos':
			$parallax_data = 'data-image="'.$background_image.'" data-width="'.$background_image_width.'" data-height="'.$background_image_height.'"';
		break;
	}
	
	$return_html.= '" ';
	
	if(empty($background_parallax) && !empty($background))
	{
		$return_html.= 'style="background-image:url('.$background_image.');background-size:cover;" ';
	}
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= $parallax_data;
	
	$return_html.= '>';
	
	$return_html.= '<div class="page_content_wrapper" style="text-align:center">';
	
	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content))
	{
		$return_html.= '<br/><br/>';
	}
	
	$return_html.= do_shortcode('[tg_testimonial_slider cat="'.$cat.'" items="'.$items.'"]');
	$return_html.= '</div>';
	
	$return_html.= '</div>';

	return $return_html;
}

add_shortcode('ppb_testimonial', 'ppb_testimonial_func');


function ppb_contact_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'title' => '',
		'address' => '',
		'background' => '',
		'background_parallax' => 'none',
		'custom_css' => '',
	), $atts));
	
	$return_html = '<div class="one withsmallpadding ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg ';
	}
	
	if(!empty($background_parallax))
	{
		$return_html.= 'parallax';
	}
	$return_html.= '"';
	
	$parallax_data = '';
	
	//Get image width and height
	$pp_background_image_id = pp_get_image_id($background);
	$background_image_arr = wp_get_attachment_image_src($pp_background_image_id, 'original');
	
	$background_image = $background_image_arr[0];
	$background_image_width = $background_image_arr[1];
	$background_image_height = $background_image_arr[2];

	//Check parallax background
	switch($background_parallax)
	{
		case 'scroll_pos':
			$parallax_data = 'data-image="'.$background_image.'" data-width="'.$background_image_width.'" data-height="'.$background_image_height.'"';
		break;
	}
	
	if(empty($background_parallax) && !empty($background))
	{
		$return_html.= 'style="background-image:url('.$background_image.');background-size:cover;" ';
	}
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= $parallax_data;
	$return_html.= '>';
	
	$return_html.= '<div class="page_content_wrapper" style="text-align:center">';
	
	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content))
	{
		$return_html.= '<br/><br/>';
	}
	
	$return_html.= '<div style="text-align:left">';
	
	//Displat address
	$return_html.= '<div class="one_half">';
	$return_html.= do_shortcode(tg_apply_content(urldecode($address)));
	$return_html.= '</div>';
	
	//Display contact form
	$return_html.= '<div class="one_half last">';

	//Get contact form random ID
	$custom_id = time().rand();
    $pp_contact_form = unserialize(get_option('pp_contact_form_sort_data'));
    wp_enqueue_script("jquery.validate", get_template_directory_uri()."/js/jquery.validate.js", false, THEMEVERSION, true);
    wp_enqueue_script("script-contact-form", get_template_directory_uri()."/templates/script-contact-form.php?form=".$custom_id, false, THEMEVERSION, true);

    $return_html.= '<div id="reponse_msg_'.$custom_id.'" class="contact_form_response"><ul></ul></div>';
    
    $return_html.= '<form id="contact_form_'.$custom_id.'" class="contact_form_wrapper" method="post" action="/wp-admin/admin-ajax.php">';
	$return_html.= '<input type="hidden" id="action" name="action" value="pp_contact_mailer"/>';

    if(is_array($pp_contact_form) && !empty($pp_contact_form))
    {
        foreach($pp_contact_form as $form_input)
        {
        	switch($form_input)
        	{
    				case 1:
    				
    				$return_html.= '<label for="your_name">'.__( 'Name *', THEMEDOMAIN ).'</label>
    				<input id="your_name" name="your_name" type="text" class="required_field" placeholder="'.__( 'Name *', THEMEDOMAIN ).'"/>
    				';	

    				break;
    				
    				case 2:
    				
    				$return_html.= '<label for="email">'.__( 'Email *', THEMEDOMAIN ).'</label>
    				<input id="email" name="email" type="text" class="required_field email" placeholder="'.__( 'Email *', THEMEDOMAIN ).'"/>
    				';	

    				break;
    				
    				case 3:
    				
    				$return_html.= '<label for="message">'.__( 'Message *', THEMEDOMAIN ).'</label>
    				<textarea id="message" name="message" rows="7" cols="10" class="required_field" style="width:91%;" placeholder="'.__( 'Message *', THEMEDOMAIN ).'"></textarea>
    				';	

    				break;
    				
    				case 4:
    				
    				$return_html.= '<label for="address">'.__( 'Address', THEMEDOMAIN ).'</label>
    				<input id="address" name="address" type="text" placeholder="'.__( 'Address', THEMEDOMAIN ).'"/>
    				';	

    				break;
    				
    				case 5:
    				
    				$return_html.= '<label for="phone">'.__( 'Phone', THEMEDOMAIN ).'</label>
    				<input id="phone" name="phone" type="text" placeholder="'.__( 'Phone', THEMEDOMAIN ).'"/>
    				';

    				break;
    				
    				case 6:
    				
    				$return_html.= '<label for="mobile">'.__( 'Mobile', THEMEDOMAIN ).'</label>
    				<input id="mobile" name="mobile" type="text" placeholder="'.__( 'Mobile', THEMEDOMAIN ).'"/>
    				';		

    				break;
    				
    				case 7:
    				
    				$return_html.= '<label for="company">'.__( 'Company Name', THEMEDOMAIN ).'</label>
    				<input id="company" name="company" type="text" placeholder="'.__( 'Company Name', THEMEDOMAIN ).'"/>
    				';

    				break;
    				
    				case 8:
    				
    				$return_html.= '<label for="country">'.__( 'Country', THEMEDOMAIN ).'</label>				
    				<input id="country" name="country" type="text" placeholder="'.__( 'Country', THEMEDOMAIN ).'"/>
    				';
    				break;
    			}
    		}
    	}

    	$pp_contact_enable_captcha = get_option('pp_contact_enable_captcha');
    	
    	if(!empty($pp_contact_enable_captcha))
    	{
    	
    	$return_html.= '<div id="captcha-wrap">
    		<div class="captcha-box">
    			<img src="'.get_stylesheet_directory_uri().'/get_captcha.php" alt="" id="captcha" />
    		</div>
    		<div class="text-box">
    			<label>Type the two words:</label>
    			<input name="captcha-code" type="text" id="captcha-code">
    		</div>
    		<div class="captcha-action">
    			<img src="'.get_stylesheet_directory_uri().'/images/refresh.jpg"  alt="" id="captcha-refresh" />
    		</div>
    	</div>
    	<br class="clear"/><br/><br/>';
    
    }
    
    $return_html.= '<p>
    	<input id="contact_submit_btn" type="submit" value="'.__( 'Send', THEMEDOMAIN ).'"/>
    </p>';
    
	$return_html.= '</form>';
	$return_html.= '</div>';
	
	
	$return_html.= '</div>';
	
	$return_html.= '</div>';
	
	$return_html.= '</div>';

	return $return_html;
}

add_shortcode('ppb_contact', 'ppb_contact_func');


function ppb_blog_carousel_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'title' => '',
		'items' => '',
		'category' => '',
		'custom_css' => '',
	), $atts));
	
	$return_html = '<div class="one withsmallpadding" ';
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= '>';
	$return_html.= '<div class="page_content_wrapper" style="text-align:center">';
	
	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content))
	{
		$return_html.= '<br/><br/>';
	}
	
	//Begin blog slider code
	//Get blog posts
	$args = array(
	    'numberposts' => $items,
	    'order' => 'DESC',
	    'orderby' => 'post_date',
	    'post_type' => array('post'),
	);

	if(!empty($category))
	{
		$args['category'] = $category;
	}
	$posts_arr = get_posts($args);
	
	if(!empty($posts_arr) && is_array($posts_arr))
	{
		wp_enqueue_style("flexslider-css", get_template_directory_uri()."/js/flexslider/flexslider.css", false, THEMEVERSION, "all");
		wp_enqueue_script("flexslider-js", get_template_directory_uri()."/js/flexslider/jquery.flexslider-min.js", false, THEMEVERSION, true);
		wp_enqueue_script("sciprt-blog-flexslider", get_template_directory_uri()."/templates/script-blog-flexslider.php", false, THEMEVERSION, true);
	
		$return_html.= '<div class="blog_slider_wrapper">';
		$return_html.= '<div class="flexslider" data-height="750">';
		$return_html.= '<ul class="slides">';
		
		foreach($posts_arr as $key => $ppb_post)
		{
			$return_html.= '<li>';
			$return_html.= '<div class="post type-post">';
			$return_html.= '<div class="post_wrapper">';
			$return_html.= '<div class="post_content_wrapper fullwidth">';
		
			$image_thumb = '';
										
			if(has_post_thumbnail($ppb_post->ID, 'large'))
			{
			    $image_id = get_post_thumbnail_id($ppb_post->ID);
			    $image_thumb = wp_get_attachment_image_src($image_id, 'large', true);
			}
			
			if(!empty($image_thumb))
			{
			    $small_image_url = wp_get_attachment_image_src($image_id, 'blog', true);
				
				$return_html.= '<div class="post_img fullwidth">
			        <a href="'.get_the_permalink($ppb_post->ID).'">
			        	<img src="'.$small_image_url[0].'" alt="" class="" style="width:'.$small_image_url[1].'px;height:'.$small_image_url[2].'px;"/>
				    	<div class="mask">
			            	<div class="mask_circle">
				                <i class="fa fa-external-link"/></i>
				    		</div>
				        </div>
				    </a>
			    </div>';    
			}
			
			//Check post format
		    $post_format = get_post_format(get_the_ID());
			
			switch($post_format)
			{
				case 'quote':
					
					$return_html.= '<div class="post_header fullwidth">
						<h5><a href="'.get_the_permalink($ppb_post->ID).'">'.get_the_content($ppb_post->ID).'</a></h5>
						
						<div class="post_detail">
						    '.get_the_time(THEMEDATEFORMAT, $ppb_post->ID).'&nbsp;/';
						
					$return_html.= '&nbsp;<a href="'.get_comments_link($ppb_post->ID).'">'.get_comments_number($ppb_post->ID);
					
					$num_comments = get_comments_number($ppb_post->ID);
					
					if ( comments_open($ppb_post->ID) ) {
						if ( $num_comments == 0 ) {
							$comments = __('No Comments', THEMEDOMAIN);
						} elseif ( $num_comments > 1 ) {
							$comments = $num_comments . __(' Comments', THEMEDOMAIN);
						} else {
							$comments = __('1 Comment', THEMEDOMAIN);
						}
						$return_html.= $comments;
					} else {
						$return_html.=  __('Comments are off for this post.', THEMEDOMAIN);
					}
					
					$return_html.= '</a>
						</div>
					</div>';
					
				break;
				
				case 'link':
					
					$return_html.= '<div class="post_header fullwidth">
						<h5>'.get_the_content($ppb_post->ID).'</h5>
						
						<div class="post_detail">
						    '.get_the_time(THEMEDATEFORMAT, $ppb_post->ID).'&nbsp;/';
						
					$return_html.= '&nbsp;<a href="'.get_comments_link($ppb_post->ID).'">'.get_comments_number($ppb_post->ID);
					
					$num_comments = get_comments_number($ppb_post->ID);
					
					if ( comments_open($ppb_post->ID) ) {
						if ( $num_comments == 0 ) {
							$comments = __('No Comments', THEMEDOMAIN);
						} elseif ( $num_comments > 1 ) {
							$comments = $num_comments . __(' Comments', THEMEDOMAIN);
						} else {
							$comments = __('1 Comment', THEMEDOMAIN);
						}
						$return_html.= $comments;
					} else {
						$return_html.=  __('Comments are off for this post.', THEMEDOMAIN);
					}
					
					$return_html.= '</a>
						</div>
					</div>';
					
				break;
				
				default:
					
					$return_html.= '<div class="post_header fullwidth">
						<h5><a href="'.get_the_permalink($ppb_post->ID).'">'.$ppb_post->post_title.'</a></h5>
						
						<div class="post_detail">
						    '.get_the_time(THEMEDATEFORMAT, $ppb_post->ID).'&nbsp;/';
						
					$return_html.= '&nbsp;<a href="'.get_comments_link($ppb_post->ID).'">'.get_comments_number($ppb_post->ID);
					
					$num_comments = get_comments_number($ppb_post->ID);
					
					if ( comments_open($ppb_post->ID) ) {
						if ( $num_comments == 0 ) {
							$comments = __('No Comments', THEMEDOMAIN);
						} elseif ( $num_comments > 1 ) {
							$comments = $num_comments . __(' Comments', THEMEDOMAIN);
						} else {
							$comments = __('1 Comment', THEMEDOMAIN);
						}
						$return_html.= $comments;
					} else {
						$return_html.=  __('Comments are off for this post.', THEMEDOMAIN);
					}
					
					$return_html.= '</a>
						</div><hr class="thick"/>';
						
					$pp_blog_display_full = get_option('pp_blog_display_full');
				    	
				    if(!empty($pp_blog_display_full))
				    {
				        $return_html.= get_the_content($ppb_post->ID);
				    }
				    else
				    {
				        $return_html.= get_excerpt_by_id($ppb_post->ID);
				    }
				    
				    $return_html.= '<a class="readmore" href="'.get_the_permalink($ppb_post->ID).'">'.__( 'Read More', THEMEDOMAIN ).'</a>';
						
					$return_html.= '</div>';
					
				break;
			}
			
			$return_html.= '</div>';
			$return_html.= '</div>';
			$return_html.= '</div>';
			$return_html.= '</li>';
		}
		
		$return_html.= '</ul>';
		$return_html.= '</div>';
		$return_html.= '</div>';
	}
	
	$return_html.= '</div>';
	$return_html.= '</div>';

	return $return_html;
}

add_shortcode('ppb_blog_carousel', 'ppb_blog_carousel_func');
?>