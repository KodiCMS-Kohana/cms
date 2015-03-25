<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Cli installer for KodiCMS
 *
 * It can accept the following options:
 *  - db_server: Mysql server (default - localhost)
 *  - db_driver: Mysql driver
 *  - db_port: Mysql port (default - 3306) 
 *  - db_user: Mysql user (default - root)
 *  - db_password: Mysql password (default - empty)
 *  - db_name: Mysql database
 *  - db_table_prefix: Mysql database prefix (default - empty)
 *  - site_name: CMS site title
 *  - username: Admin username
 *  - password: Admin password (default - auto generate)
 *  - email: Admin email (default - admin@yoursite.com)
 *  - admin_dir_name: Directory to access backend intarface (default - backend)
 *  - url_suffix: URL suffix append to url (default - .html)
 *  - timezone: Current timezone (http://www.php.net/manual/en/timezones.php)
 *  - empty_database: Clear selected database before import new data
 *  - cache_type: Cache type
 *  - session_type: Session type
 *  - locale: Current site locale

 * @package		KodiCMS/Installer
 * @category	Task
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Task_Install extends Minion_Task
{
	/**
	 *
	 * @var Installer
	 */
	protected $_installer;
	
	protected $_options = array(
		'db_driver' => NULL,
		'db_password' => NULL,
		'db_name' => NULL,
		'db_table_prefix' => '',
		'password' => NULL,
		'password_generate' => TRUE,
		'empty_database' => FALSE,
		'cache_type' => NULL,
		'session_type' => NULL,
		'locale' => NULL
	);
	
	protected function __construct()
	{
		$this->_installer = new Installer;

		$default = $this->_installer->default_params();
		$this->_options = Arr::merge($this->_options, $default);

		parent::__construct();
	}
	
	public function build_validation(Validation $validation)
	{
		$config = Kohana::$config->load('installer');

		$locales = array_keys(I18n::available_langs());

		return parent::build_validation($validation)
			->rule('db_server', 'not_empty')
			->rule('db_port', 'not_empty')
			->rule('db_port', 'numeric')
			->rule('db_user', 'not_empty')
			->rule('username', 'not_empty')
			->rule('email', 'not_empty')
			->rule('email', 'email')
			->rule('cache_type', 'not_empty')
			->rule('cache_type', 'in_array', array(':value', array_keys($this->_installer->cache_types())))
			->rule('session_type', 'in_array', array(':value', array_keys($this->_installer->session_types())))
			->rule('db_driver', 'in_array', array(':value', array_keys($this->_installer->database_drivers())))
			->rule('locale', 'in_array', array(':value', $locales))
			->rule('admin_dir_name', 'not_empty');
	}

	protected function _execute(array $params)
	{		
		if ($params['db_driver'] === NULL)
		{
			$params['db_driver'] = Minion_CLI::read(__('Please enter database driver (:types)', array(
				':types' => implode(', ', array_keys($this->_installer->database_drivers()))
			)));
		}
		
		if ($params['locale'] === NULL)
		{
			$params['locale'] = Minion_CLI::read(__('Please enter locale (:types)', array(
				':types' => implode(', ', array_keys(I18n::available_langs()))
			)));
		}

		if ($params['db_name'] === NULL)
		{
			$params['db_name'] = Minion_CLI::read(__('Please enter database name'));
		}

		if ($params['timezone'] === NULL)
		{
			$answer = Minion_CLI::read(__('Select current timezone automaticly (:current)', array(':current' => date_default_timezone_get())), array('y', 'n'));

			if ($answer == 'y')
			{
				$params['timezone'] = date_default_timezone_get();
			}
			else
			{
				$params['timezone'] = Minion_CLI::read(__('Please enter current timezone (:site)', array(':site' => 'http://www.php.net/manual/en/timezones.php')), DateTimeZone::listIdentifiers());
			}
		}

		if ($params['cache_type'] === NULL)
		{
			$params['cache_type'] = Minion_CLI::read(__('Please enter cache type (:types)', array(
				':types' => implode(', ', array_keys($this->_installer->cache_types()))
			)));
		}
		
		if ($params['session_type'] === NULL)
		{
			$session_types = Kohana::$config->load('installer')->get('session_types', array());
			$params['session_type'] = Minion_CLI::read(__('Please enter session type (:types)', array(
				':types' => implode(', ', array_keys($this->_installer->session_types()))
			)));
		}

		if ($params['password'] !== NULL)
		{
			unset($params['password_generate']);
			$params['password_field'] = $params['password_confirm'] = $params['password'];
		}

		try
		{
			$this->_installer->install($params);
			Observer::notify('after_install', $params);
			Cache::clear_file();
			
			Minion_CLI::write('==============================================');
			Minion_CLI::write(__('KodiCMS installed successfully'));
			Minion_CLI::write('==============================================');

			$install_data = Session::instance()->get_once('install_data');
			Minion_CLI::write(__('Login: :login', array(':login' => Arr::get($install_data, 'username'))));
			Minion_CLI::write(__('Password: :password', array(':password' => Arr::get($install_data, 'password_field'))));
		}
		catch (Exception $e)
		{
			Minion_CLI::write(__(':text | :file [:line]', array(
				':text' => $e->getMessage(),
				':file' => $e->getFile(),
				':line' => $e->getLine()
			)));
		}
	}
}