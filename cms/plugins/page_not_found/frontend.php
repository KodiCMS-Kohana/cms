<?php defined('SYSPATH') or die('No direct access allowed.');

function behavior_page_not_found()
{
	$page = DB::select()
		->from('page')
		->where('behavior_id', '=', 'page_not_found')
		->limit(1)
		->as_object()
		->execute()
		->current();

	if ($page)
	{
		$page = FrontPage::find( $page->slug );
		
		// if we fund it, display it!
		if( is_object($page) )
		{
			header("HTTP/1.0 404 Not Found");
			header("Status: 404 Not Found");
			  
			$page->display();
			exit(); // need to exit here otherwise the true error page will be sended
		}
	}
}

// Observe
Observer::observe('page_not_found', 'behavior_page_not_found');