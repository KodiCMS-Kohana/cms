<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	User
 * @author		ButscHSter
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
