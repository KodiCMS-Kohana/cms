<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	Exception
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_HTTP_Exception_401 extends Kohana_HTTP_Exception_401 
{

	/**
	* Generate a Response for the 401 Exception.
	* 
	* The user should be redirect to a login page.
	* 
	* @return Response
	*/
	public function get_response()
	{
		Flash::set('redirect', Request::current()->uri());
	
		$response = Response::factory()
			->status(401)
			->headers('Location', BASE_URL . Route::get('user')->uri(array(
				'action' => 'login'
			)));
	
		return $response;
	}
}