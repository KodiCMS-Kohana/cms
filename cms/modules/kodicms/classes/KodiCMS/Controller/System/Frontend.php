<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	System Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Controller_System_Frontend extends Controller_System_Template
{
	public function before()
	{
		parent::before();

		if ($this->auto_render === TRUE)
		{
			Assets::js('jquery', ADMIN_RESOURCES . 'libs/jquery.min.js');

			Assets::package(array('jquery-ui', 'backbone', 'notify', 'underscore', 'select2', 'core', 'bootstrap'));
		}
	}
}