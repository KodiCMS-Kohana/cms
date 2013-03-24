<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_User_Login extends Model_Widget_Decorator {
	
	public $use_template = FALSE;
	
	protected $_data = array(
		'login_field' => 'username',
		'password_field' => 'password',
		'remember_field' => 'remember',
		'next_url' => '/',
		'remember' => TRUE
	);
	
	public function set_values(array $data)
	{
		$data['remember'] = empty($data['remember']) ? FALSE : (bool) $data['remember'];

		return parent::set_values($data);
	}

	public function fetch_data()
	{
		
	}

	public function render($params = array())
	{
		if(Request::current()->method() !== Request::POST) return;

		$data = Request::current()->post();
		
		$login_fieldname = Valid::email( Arr::get($data, $this->get( 'login_field' )) ) 
			? AuthUser::EMAIL 
			: AuthUser::USERNAME;
		
		$data = Validation::factory( $data )
			->label( $this->get( 'login_field' ), 'Username' )
			->label( $this->get( 'password_field' ), 'Password' )
			->rules( 'username', array(
				array( 'not_empty' )
			) )
			->rules( 'password', array(
				array( 'not_empty' ),
			) );
		
		// Get the remember login option
		$remember = isset( $data[$this->get( 'remember_field' )] ) AND $this->get( 'remember' ) === TRUE;
		
		if ( $data->check() )
		{			
			if ( AuthUser::login( $login_fieldname, $data[$this->get( 'login_field' )], $data[$this->get( 'password_field' )], $remember ) )
			{
				HTTP::redirect($this->get('next_url', Request::current()->referrer()));
			}
			else
			{
				Messages::errors( __('Login failed. Please check your login data and try again.') );
			}
		}
		else
		{
			Messages::errors( $data->errors( 'validation' ) );
		}

		HTTP::redirect($this->get( Request::current()->referrer()) );
	}
}