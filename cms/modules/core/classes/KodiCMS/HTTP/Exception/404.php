<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS
 * @category	Exception
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_HTTP_Exception_404 extends Kohana_HTTP_Exception_404 {

	/**
	 * Generate a Response for the 404 Exception.
	 * 
	 * @return Response
	 */
	public function get_response()
	{
		$ext = pathinfo(Request::current()->url(), PATHINFO_EXTENSION);
		$mimetype = FALSE;

		if ($ext AND !($mimetype = File::mime_by_ext($ext)))
		{
			$mimetype = 'application/octet-stream';
		}
		
		if ($mimetype)
		{
			return Response::factory()
				->headers('content-type', $mimetype)
				->status(404);
		}
		else
		{
			return parent::get_response();
		}
	}
}
