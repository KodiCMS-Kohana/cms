<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class HTTP_Exception_404 extends Kohana_HTTP_Exception_404 
{
	public function get_response()
	{
		$page = DB::select()
			->from(Model_Page::TABLE_NAME)
			->where('behavior_id', '=', 'page_not_found')
			->limit(1)
			->as_object()
			->execute()
			->current();
		
		if($page)
		{
			$page = Model_Page_Front::find( $page->slug );

			// if we fund it, display it!
			if( is_object($page) )
			{				
				return Request::factory($page->url)
					->execute();
			}
		}
	}
}