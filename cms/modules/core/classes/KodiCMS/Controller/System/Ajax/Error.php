<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	System Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Controller_System_Ajax_Error extends Controller_System_Template 
{
	public $auth_required = FALSE;

	public function action_index()
	{
		$this->json['message'] = '';
		$this->json['uri'] = URL::site(rawurldecode(Request::$initial->uri()));
		if ($message = rawurldecode($this->request->param('id')))
		{
			$this->json['message'] = $message;
		}
	}
}