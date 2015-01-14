<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Page_Password extends Model_Widget_Decorator {
	
	public $cache_tags = array('pages');
	
	public function on_page_load()
	{
		parent::on_page_load();

		if (Request::current()->method() == Request::POST)
		{
			return $this->_check_password();
		}
	}
	
	/**
	 * 
	 * @return array [$current_page]
	 */
	public function fetch_data()
	{
		return array(
			'current_page' => Session::instance()->get('protected_page')
		);
	}
	
	protected function _check_password()
	{
		$password = Request::current()->post('password');

		$session = Session::instance();
		$page = $session->get('protected_page');

		if ($page->password == $password)
		{
			$pages = $session->get('page_access', array());

			$pages[$page->id] = TRUE;
			$session
				->set('page_access', $pages)
				->delete('protected_page');
		}

		HTTP::redirect($page->url());
	}
}