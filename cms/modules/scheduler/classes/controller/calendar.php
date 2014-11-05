<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Calendar
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Calendar extends Controller_System_Backend {

	public function before()
	{
		parent::before();
		
		$this->breadcrumbs
			->add(__('Calendar'), Route::get('backend')->uri(array('controller' => 'calendar')));
	}
	
	public function action_index()
	{
		Assets::package('jquery-ui');
		Assets::css('fullcalendar', ADMIN_RESOURCES . 'libs/fullcalendar-2.1.0/fullcalendar.min.css', 'global');
		Assets::js('fullcalendar', ADMIN_RESOURCES . 'libs/fullcalendar-2.1.0/fullcalendar.min.js', 'jquery');
		Assets::js('fullcalendar.lang', ADMIN_RESOURCES . 'libs/fullcalendar-2.1.0/lang/' . I18n::lang_short() . '.js', 'fullcalendar');
		
		$this->set_title(__('Calendar'), FALSE);
		
		$this->template->content = View::factory('calendar/index', array(
			'colors' => array('default', 'darken', 'danger', 'info', 'primary', 'success', 'warning'),
			'icons' => array('info', 'warning', 'check', 'user', 'lock', 'clock-o')
		));
	}
}
