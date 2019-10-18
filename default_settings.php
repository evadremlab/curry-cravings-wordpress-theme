<?php
	$current_ggfont = get_option(SHORTNAME."_ggfont");
	//var_dump($current_ggfont); die;
	
	if(!isset($current_ggfont['Montserrat']))
	{
	    $current_ggfont['Montserrat'] = 'Montserrat';
	}
	
	if(!isset($current_ggfont['Dosis']))
	{
	    $current_ggfont['Dosis'] = 'Dosis';
	}
	
	if(!isset($current_ggfont['Abel']))
	{
	    $current_ggfont['Abel'] = 'Abel';
	}
	
	if(!isset($current_ggfont['Ubuntu']))
	{
	    $current_ggfont['Ubuntu'] = 'Ubuntu';
	}
	
	if(!isset($current_ggfont['Oxygen']))
	{
	    $current_ggfont['Oxygen'] = 'Oxygen';
	}
	
	if(!isset($current_ggfont['Source Sans Pro']))
	{
	    $current_ggfont['Source Sans Pro'] = 'Source Sans Pro';
	}
	
	if(!isset($current_ggfont['Roboto Condensed']))
	{
	    $current_ggfont['Roboto Condensed'] = 'Roboto Condensed';
	}
	
	if(!isset($current_ggfont['Pathway Gothic One']))
	{
	    $current_ggfont['Pathway Gothic One'] = 'Pathway Gothic One';
	}
		
	if(!empty($current_ggfont))
	{
		update_option( SHORTNAME."_ggfont", $current_ggfont );
	}
	else
	{
		add_option( SHORTNAME."_ggfont", $current_ggfont );
	}
?>