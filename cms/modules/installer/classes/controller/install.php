<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Installer
 * @category	Controller
 * @author		ButscHSter
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
			$this->_complete($post);
		}
		catch (Exception $ex)
		{
			$this->_show_error($ex);
		}
		
	}
	
	public function action_check_connect()
	{
		$post = $this->request->post('install');
		$this->_validation = Validation::factory($post)
			->label('db_server', __('Database server'))
			->label('db_user', __( 'Database user' ))
			->label('db_password', __('Database password'))
			->label('db_name', __('Database name'))
			->rule( 'db_server', 'not_empty' )
			->rule( 'db_user', 'not_empty' )
			->rule( 'db_name', 'not_empty' );
	
		try
		{
			if (!$this->_validation->check())
			{
				throw new Validation_Exception($this->_validation);
			}

			$this->json['status'] = (bool) $this->_connect_to_db($post);
		}
		catch (Kohana_Exception $e)
		{
			if ($e instanceof Validation_Exception)
			{
				$this->json['message'] = $e->errors('validation');
			}
			else
			{
				$this->json['message'][] = $e->getMessage();
			}

			$this->json['status'] = FALSE;
		}
	}
	
	/**
	 * 
	 * @param array $post
	 */
	protected function _complete($post)
	{
		Observer::notify('after_install', $post);
		Cache::clear_file();

		$this->go($post['admin_dir_name'] . '/login');
	}

	/**
	 * Вывод ошибок
	 * @param Exception $e
	 */
	protected function _show_error(Exception $e)
	{
		if ($e instanceof Validation_Exception)
		{
			Messages::errors($e->errors('validation'));
		}
		else
		{
			Messages::errors($e->getMessage());
		}

		$this->go_back();
	}
}