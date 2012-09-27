<?php defined('SYSPATH') or die('No direct access allowed.');

$plugin = Model_Plugin_Item::factory( array(
	'id' => 'page_not_found',
	'title' => 'Page not found',
	'description' => 'Provides Page not found type.'
) )->register();

if($plugin->enabled())
{	
	if(IS_BACKEND)
	{
		Behavior::add('page_not_found', '');
	}
	else
	{
		// Observe
		Observer::observe('page_not_found', 'behavior_page_not_found');
	}
}

function behavior_page_not_found()
{
	$page = DB::select()
		->from(Page::TABLE_NAME)
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
			Request::current()
				->response()
				->status(404);

			echo $page->render_layout();
			exit(); // need to exit here otherwise the true error page will be sended
		}
	}
}