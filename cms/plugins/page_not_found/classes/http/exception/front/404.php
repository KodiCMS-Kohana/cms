<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class HTTP_Exception_Front_404 extends Kohana_HTTP_Exception_404 
{
	public function get_response()
	{
		$ext = pathinfo(Request::current()->url(), PATHINFO_EXTENSION);
		$mimetype = FALSE;

		if ($ext AND ! ($mimetype = File::mime_by_ext($ext)))
		{
			$mimetype = 'application/octet-stream';
		}

		if ($mimetype AND $mimetype !== 'text/html')
		{
			return Response::factory()
				->headers('content-type', $mimetype)
				->status(404);
		}
			
		if(($page = Model_Page_Front::findByField('behavior_id', 'page_not_found')) !== FALSE)
		{
			return Request::factory($page->url)
				->query('message', $this->message)
				->execute()
				->status(404);
		}

		throw new HTTP_Exception_404('Something went wrong');
	}
}