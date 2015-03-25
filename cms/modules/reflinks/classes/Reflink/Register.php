<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Reflink
 * @category	Drivers
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Reflink_Register extends Reflink {
	
	public function confirm()
	{
		try
		{
			$role = ORM::factory('role', array('name' => 'login'));
			$this->_model->user->add('roles', $role);
			
			Email_Type::get('user_registered')->send(array(
				'username' => $this->_model->user->username,
				'email' => $this->_model->user->email
			));
			
			Messages::success(__('Thank you for registration!'));
			
			$this->_model->delete();

			return TRUE;
		}
		catch (Kohana_Exception $e)
		{
			throw new Reflink_Exception('Something went wrong');
		}
	}
}