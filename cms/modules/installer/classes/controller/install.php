<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Installer
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Install extends Controller_System_Frontend 
{
	public $template = 'system/frontend';
	
	/**
	 *
	 * @var Installer
	 */
	protected $_installer;

	public function before()
	{
		$this->_installer = new Installer;
		parent::before();
	}

	public function action_error()
	{
		$this->template->title = __(':cms_name &rsaquo; error', array(':cms_name' => CMS_NAME));
		$this->template->content = View::factory('install/error', array(
			'title' => $this->template->title
		));
	}
	
	public function action_index()
	{
		Assets::package(array('select2', 'validate', 'install', 'steps'));

		$this->template->title = __(':cms_name &rsaquo; installation', array(':cms_name' => CMS_NAME));
		
		$this->template->content = View::factory('install/index', array(
			'data' => Session::instance()->get_once('install_data', $this->_installer->default_params()),
			'env_test' => View::factory('install/env_test'),
			'cache_types' => $this->_installer->cache_types(),
			'session_types' => $this->_installer->session_types(),
			'database_drivers' => $this->_installer->database_drivers(),
			'title' => $this->template->title,
			'dates' => Date::formats()
		));
	}

	/**
	 * 
	 * @throws Installer_Exception
	 */
	public function action_go()
	{
		$this->auto_render = FALSE;

		$post = $this->request->post('install');
		
		try
		{
			$this->_installer->install($post);
			
			Observer::notify('after_install', $post);
			Cache::clear_file();			
		}
		catch (Validation_Exception $e)
		{
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}
		catch (Exception $e)
		{
			Messages::errors($e->getMessage());
			$this->go_back();
		}
		
		$this->go($post['admin_dir_name'] . '/login');
	}
}