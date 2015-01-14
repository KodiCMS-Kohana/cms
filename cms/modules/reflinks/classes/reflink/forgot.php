<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Reflink
 * @category	Drivers
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Reflink_Forgot extends Reflink {
	
	public function confirm()
	{
		$new_password = Text::random();
		$this->_model->user->change_email( $new_password );
		
		try
		{
			Email_Type::get('user_new_password')->send(array(
				'username' => $this->_model->user->username,
				'email' => $this->_model->user->email,
				'password' => $new_password
			));
			
			Messages::success(__('An email has been send with your new password!'));
			
			$this->_model->delete();
			
			return TRUE;
		}
		catch (Kohana_Exception $e)
		{
			throw new Reflink_Exception('Email :email not send', array(
				':email' => $this->_model->user->email));
		}
	}
}