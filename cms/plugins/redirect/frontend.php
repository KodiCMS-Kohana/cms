<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe('frontpage_requested', function() {
	$plugin = Plugins::get_registered('redirect');
	
	$redirect = FALSE;
	$current_uri = $_SERVER['REQUEST_URI'];
	$path = $_SERVER['HTTP_HOST'] . $current_uri;
	
	$domain = $plugin->domain;

	if( ! empty($domain) AND $_SERVER['HTTP_HOST'] != $domain) 
	{
		$path = $domain . $current_uri;
		$redirect = TRUE;
	}

	if($plugin->check_url_suffix == Config::YES) 
	{
		$current_uri = trim($current_uri, '/');

		if(
			strpos($path, URL_SUFFIX) === FALSE 
		AND 
			! empty($current_uri) 
		AND 
			$current_uri != 'index.php'
		) 
		{
			$redirect = TRUE;
			$path .= URL_SUFFIX;
		}
	}
	
	$protocol = Request::$initial->secure() ? 'https' : 'http';

	if($redirect === TRUE)
	{
		$protocol = Request::$initial->secure() ? 'https' : 'http';
		HTTP::redirect($protocol.'://' . $path, 301);
	}
});