<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_User_Forgot extends Model_Widget_Decorator_Handler {

	protected $_data = array(
		'email_id_ctx' => 'email',
		'next_url' => '/',
	);

	public function on_page_load()
	{
		$email_ctx_id = $this->get('email_id_ctx', 'email');
		$email = $this->_ctx->get($email_ctx_id);

		$referrer_page = Request::current()->referrer();
		$next_page = $this->get('next_url', Request::current()->referrer());

		if (!Valid::email($email))
		{
			Messages::errors(__('Use a valid e-mail address.'));
			HTTP::redirect($referrer_page);
		}

		$user = ORM::factory('user', array(
			'email' => $email
		));
		
		if (!$user->loaded())
		{
			Messages::errors(__('No user found!'));
			HTTP::redirect($referrer_page);
		}

		$reflink = ORM::factory('user_reflink')->generate($user, 'forgot', array(
			'next_url' => URL::site($this->next_url, TRUE)
		));

		if (!$reflink)
		{
			Messages::errors(__('Reflink generate error'));
			HTTP::redirect($referrer_page);
		}

		Observer::notify('admin_login_forgot_before', $user);
		
		try
		{
			Email_Type::get('user_request_password')->send(array(
				'username' => $user->username,
				'email' => $user->email,
				'reflink' => Route::url('reflink', array('code' => $reflink)),
				'code' => $reflink
			));

			Messages::success(__('Email with reflink send to address set in your profile'));
		} 
		catch (Exception $e)
		{
			Messages::error(__('Something went wrong'));
		}
		
		HTTP::redirect($next_page);
	}
}