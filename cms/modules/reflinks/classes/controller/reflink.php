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
					if($this->_forgot($reflink, $status));
					break;
			}
			
			Database::instance()->commit();
		}
		catch (Exception $e )
		{
			Database::instance()->rollback();
			Messages::errors( __('Something went wrong' ));
		}
		
		$this->go( Route::get('user')->uri(array( 'action' => 'login' ) ) );
	}
	
	protected function _forgot($reflink, $new_password)
	{
		Messages::success(__('Password send to email address'));
		
		$message = View::factory('messages/email/forgot', array(
			'username' => ucwords( $reflink->user->username ),
			'password' => $new_password
		));
		
		$site_host = dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);

		$email = new Email();
		$email->from('no-reply@' . $site_host, Setting::get('admin_title'));
		$email->to($reflink->user->email);
		$email->subject(__('New password for :site_name', array(':site_name' => Setting::get('admin_title'))));
		$email->message($message);
		$email->send();

		return $reflink->delete();
	}
}