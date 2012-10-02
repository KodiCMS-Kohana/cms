<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Login extends Controller_System_Template {

	public $template = 'layouts/frontend';

	public function before()
	{
		parent::before();
		if (
			$this->request->action() != 'logout'
			AND
			AuthUser::isLoggedIn()
		)
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
		if ( $this->request->method() == Request::POST )
		{
			$this->auto_render = FALSE;
			return $this->_login();
		}

		$this->template->content = View::factory( 'system/login' );
		
		$this->template->content->install_data = Session::instance()->get_once('install_data');
	}

	private function _login()
	{
		$array = Arr::get($_POST, 'login', array());

		$fieldname = Valid::email( Arr::get($array, 'username') ) ? 'email' : 'username';

		$array = Validation::factory( $array )
			->label( 'username', 'Username / Email' )
			->label( 'password', 'Password' )
			->rules( 'username', array(
				array( 'not_empty' )
			) )
			->rules( 'password', array(
				array( 'not_empty' ),
			) );

		// Get the remember login option
		$remember = isset( $array['remember'] );

		if ( $array->check() )
		{
			Observer::notify( 'admin_login_before', array( $array ) );

			if ( AuthUser::login( $array['username'], $array['password'], $remember ) )
			{
				Observer::notify( 'admin_login_success', array( $array['username'] ) );

				Session::instance()->delete('install_data');

				// $this->go to defaut controller and action
				$this->go_backend();
			}
			else
			{
				Observer::notify( 'admin_login_failed', array( $array['username'] ) );
				$array->error( $fieldname, 'incorrect' );
			}
		}

		Messages::errors( $array->errors( 'validation' ) );

		$this->go( Route::url( 'user', array( 'action' => 'login' ) ) );
	}

	public function action_logout()
	{
		$this->auto_render = FALSE;

		AuthUser::logout();
		Observer::notify('admin_after_logout', array(AuthUser::getUserName()));

		$this->go_home();
	}

	public function action_forgot()
	{
		if ( $this->request->method() == Request::POST )
		{
			$this->auto_render = FALSE;
			$this->_forgot(Arr::path($_POST, 'forgot.email'));
		}

		$this->template->content = View::factory( 'system/forgot' );
	}

	private function _forgot($email)
	{
		if(!Valid::email( $email ))
		{
			Messages::errors( __('Email address no valid') );
			$this->go( Route::url( 'user', array( 'action' => 'forgot' ) ) );
		}
		
		$user = ORM::factory('user', array(
			'email' => $email
		));
		
		if(!$user->loaded())
		{
			Messages::errors( __('No user found!') );
			$this->go( Route::url( 'user', array( 'action' => 'forgot' ) ) );
		}

		$user = User::findBy('email', $email);

		Observer::notify('admin_login_forgot_before', array($user));

		Session::instance()->set('forgot_email', $email);

		$new_pass = Text::random();
		$user->password = Auth::instance()->hash($new_pass);
		$user->save();

		$message = (string) View::factory('messages/forgot_emil', array(
			'username' => $user->username,
			'password' => $new_pass
		));

		$site_host = dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);

		$email = new Email();
		$email->from('no-reply@' . $site_host, Setting::get('admin_title'));
		$email->to($user->email);
		$email->subject(__('Your new password from :site_name', array(':site_name' => Setting::get('admin_title'))));
		$email->message($message);
		$email->send();

		Messages::success( __('An email has been send with your new password!') );
		$this->go( Route::url( 'user', array( 'action' => 'login' ) ) );

	}

}

// end LoginController class
