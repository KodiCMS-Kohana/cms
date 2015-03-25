<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_User_Logout extends Model_Widget_Decorator_Handler {

	public function on_page_load()
	{
		$username = Auth::get_username();

		Auth::instance()->logout(TRUE);
		Observer::notify('admin_after_logout', $username);

		HTTP::redirect($this->get('next_url', Request::current()->referrer()));
	}
}
