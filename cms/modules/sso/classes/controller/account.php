<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Controller_Account extends Controller_System_SSO {

	/**
	 * @return Array
	 */
	abstract protected function _login_params();

	abstract protected function _do_login();

	/**
	 * @var string  Session status key
	 */
	protected $_status_key  = 'SSO_accounting';

	protected $_referer_key = 'SSO_referer';

	public function action_login()
	{
		$this->_save_referer('account/identify', $this->_changed_uri('complete_login'));
		$this->_do_login();
	}
	
	public function action_connect()
	{
		if( ! AuthUser::isLoggedIn())
		{
			Messages::errors(__('User must be logged in'));
		}

		$this->_save_referer('account/identify', $this->_changed_uri('complete_connect'));
		$this->_do_login();
	}
	
	public function action_disconnect()
	{
		if( ! AuthUser::isLoggedIn())
		{
			Messages::errors(__('User must be logged in'));
		}
		
		$this->_save_referer('account/identify', $this->_changed_uri('complete_disconnect'));
		$this->_do_login();
	}

	public function action_complete_login()
	{
		$params = $this->_login_params();

		if (empty($params) OR call_user_func_array(array($this->_sso, 'login'), $params) === FALSE)
		{
			
		}
		else
		{
			$user = $this->_sso->get_user();
			if( $user->loaded() AND ! AuthUser::isLoggedIn() )
			{
				if($user->user->loaded())
				{
					Auth::instance()->force_login($user->user);
				}
				else
				{
					Messages::errors( __('Social account not linked!') );
					$user->delete();
				}
			}
			
		}
		$this->go_home();
	}
	
	public function action_complete_connect()
	{
		$params = $this->_login_params();

		if (empty($params) OR call_user_func_array(array($this->_sso, 'login'), $params) === FALSE)
		{
			
		}
		else
		{
			$user = $this->_sso->get_user();
			if( $user->loaded() AND $user->user_id === NULL AND AuthUser::isLoggedIn() )
			{
				$user->set('user_id', AuthUser::getId())->update();
				
				Messages::success( __('Social account connected') );
			}
			
		}
		$this->go('user/edit/'.AuthUser::getId());
	}
	
	public function action_complete_disconnect()
	{
		$params = $this->_login_params();

		if (empty($params) OR call_user_func_array(array($this->_sso, 'login'), $params) === FALSE)
		{
			
		}
		else
		{
			$user = $this->_sso->get_user();
			if( $user->loaded() AND AuthUser::isLoggedIn() )
			{
				$this->_sso->logout();
				$user->delete();
				Messages::success( __('Social account disconnected') );
			}
		}
		$this->go('user/edit/'.AuthUser::getId());
	}
}