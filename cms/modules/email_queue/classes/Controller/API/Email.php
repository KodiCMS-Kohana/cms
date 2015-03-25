<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Email
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_API_Email extends Controller_System_Api {

	public function post_send()
	{
		$subject = $this->param('subject', NULL, TRUE);
		$sender_name = $this->param('sender_name', Config::get('site', 'name'));
		$sender_email = $this->param('sender_email', Config::get('email', 'default'));

		$to = $this->param('to', NULL, TRUE);
		$message = $this->param('message', NULL, TRUE);
		$type = $this->param('type', 'text/html');

		$email = Email::factory($subject)
			->from($sender_email, $sender_name)
			->to($to)
			->message($message, $type)
			->send();

		$this->json['send'] = $email;
	}
}