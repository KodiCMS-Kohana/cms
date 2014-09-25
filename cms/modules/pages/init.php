<?php defined('SYSPATH') or die('No direct access allowed.');

Observer::observe('frontpage_requested', function($request_uri) {

	if(strlen(URL_SUFFIX) > 0 AND Config::get('site', 'check_url_suffix'))
	{
		$server_uri = $_SERVER['REQUEST_URI'];
		$server_uri = trim($server_uri, '/');
		$request_uri = $request_uri . URL_SUFFIX;

		if($server_uri !== $request_uri)
		{
			Context::instance()->throw_404();
		}
	}
});

Observer::observe('frontpage_found', function($page) {

	if ($page->is_password_protected())
	{
		throw new HTTP_Exception_Front_401('Page protected');
	}
});
