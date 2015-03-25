<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Sphinx
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Sphinx extends Controller_System_Backend {
	
	public function before()
	{
		parent::before();

		$this->breadcrumbs
			->add(__('Sphinx'), Route::get('backend')->uri(array('controller' => 'sphinx')));
	}
	
	public function action_config() 
	{
		Assets::package('ace');
		
		$this->set_title(__('Config'));
		
		$this->template->content = View::factory('sphinx/config', array(
			'config' => Config::get('search', 'sphinx', array())
		));
	}
}