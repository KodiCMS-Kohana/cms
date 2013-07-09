<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Reflink extends Controller_System_Controller {

	public function action_index()
	{
		$code = $this->request->param( 'code' );
		if ( $code === NULL )
		{
			Model_Page_Front::not_found();
		}

		$reflink = ORM::factory( 'user_reflink', $code );

		try
		{
			Database::instance()->begin();
			
			$status = $reflink->confirm();

			switch ( $reflink->type )
			{
				case Model_User_Reflink::FORGOT_PASSWORD:
					$this->_forgot($reflink, $status);
					break;
			}
			
			Database::instance()->commit();
		}
		catch ( Kohana_Exception $e )
		{
			Database::instance()->rollback();
			Messages::errors( $e->getMessage() );
		}
		
		$this->go( Route::get('user')->uri(array( 'action' => 'login' ) ) );
	}
	
	protected function _forgot($reflink, $new_password)
	{
		$message = View::factory('messages/email/forgot', array(
			'username' => ucwords( $reflink->user->username ),
			'password' => $new_password
		));

		$email = Email::factory(__('New password for :site_name', array(':site_name' => Setting::get('site_title'))))
			->from(Setting::get('default_email'), Setting::get('site_title'))
			->to($reflink->user->email)
			->message($message, 'text/html');

		if((bool) $email->send())
		{
			Messages::success(__('An email has been send with your new password!'));
			return $reflink->delete();
		}
		else
		{
			throw new Reflink_Exception('Email :email not send', array(':email' => $reflink->user->email));
		}
	}
}