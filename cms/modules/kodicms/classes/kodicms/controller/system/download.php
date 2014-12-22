<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	System Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Controller_System_Download extends Controller {
	
	public $auto_render = FALSE;

	public function action_index()
	{
		$path = Download::decode_path($this->request->param('path'));

		if (!file_exists($path))
		{
			if (IS_BACKEND)
			{
				throw new HTTP_Exception_404('File :file not found', array(
					':file' => $path));
			}
			else
			{
				Model_Page_Front::not_found('File :file not found', array(
					':file' => $path));
			}
		}

		$this->responses->send_file($path);
	}
	
}