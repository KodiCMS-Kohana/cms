<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Controller_Login extends Controller_System_Frontend {

	public $template = 'system/frontend';

	public function before()
	{
		parent::before();

		if (file_exists(CMSPATH . FileSystem::normalize_path('media/js/i18n/' . I18n::lang() . '-message.js')))
		{
			Assets::js('i18n', ADMIN_RESOURCES . 'js/i18n/' . I18n::lang() . '-message.js', 'global');
		}

		if ($this->request->action() != 'logout'
			AND Auth::is_logged_in())
		{
			$this->go_home();
		}
	}

	/**
	 * Checks if a user is already logged in, otherwise it $this->gos the user
	 * to the login screen.
	 */
	public function action_login()
	{
		if ($this->request->method() == Request::POST)
		{
			$this->auto_render = FALSE;
			return $this->_login();
		}

		$this->set_title(__('Login'));

		$this->template->content = View::factory('system/login', array(
			'install_data' => Session::instance()->get_once('install_data')
		));
	}

	private function _login()
	{
		$array = $this->request->post('login');

		$array = Validation::factory($array)
			->label('username', 'Username')
			->label('password', 'Password')
			->label('email', 'Email')
			->rules('username', array(
				array('not_empty')
			))
			->rules('password', array(
				array('not_empty'),
			));
		
		$fieldname = Valid::email(Arr::get($array, 'username')) ? Auth::EMAIL : Auth::USERNAME;

		// Get the remember login option
		$remember = isset($array['remember']);

		Observer::notify('admin_login_validation', $array);

		if ($array->check())
		{
			Observer::notify('admin_login_before', $array);

			if (Auth::instance()->login($array['username'], $array['password'], $remember))
			{
				Observer::notify('admin_login_success', $array['username']);

				Session::instance()->delete('install_data');

				Kohana::$log->add(Log::INFO, ':user login')->write();

				if ($next_url = Flash::get('redirect'))
				{
					$this->go($next_url);
				}

				// $this->go to defaut controller and action
				$this->go_backend();
			}
			else
			{
				Observer::notify('admin_login_failed', $array);

				Messages::errors(__('Login failed. Please check your login data and try again.'));
				$array->error($fieldname, 'incorrect');

				Kohana::$log->add(Log::ALERT, 'Try to login with :field: :value. Incorrect data', array(
					':field' => $fieldname,
					':value' => $array['username']
				))->write();
			}
		}
		else
		{
			Messages::errors($array->errors('validation'));
		}

		$this->go(Route::get('user')->uri(array('action' => 'login')));
	}

	public function action_logout()
	{
		$this->auto_render = FALSE;
		Auth::instance()->logout(TRUE);

		Observer::notify('admin_after_logout', Auth::get_username());

		if ($next_url = Flash::get('redirect'))
		{
			$this->go($next_url);
		}

		$this->go_home();
	}

	public function action_forgot()
	{
		if ($this->request->method() == Request::POST)
		{
			$this->auto_render = FALSE;

			$widget = Widget_Manager::factory('User_Forgot');

			Context::instance()->set('email', Arr::path($this->request->post(), 'forgot.email'));

			$widget->set_values(array(
				'next_url' => Route::get('user')->uri(array('action' => 'login'))
			))->on_page_load();
		}

		$this->set_title(__('Forgot password'));
		$this->template->content = View::factory('system/forgot');
	}

}