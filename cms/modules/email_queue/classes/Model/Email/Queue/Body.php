<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Mail Queue Body Model. Contains the actual content of the email. 
 * Splitting them off improves performance.
 * 
 * @package		KodiCMS/Email
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Email_Queue_Body extends ORM
{
	public function rules()
	{
		return array(
			'body' => array(
				array('not_empty'),
			),
		);
	}
}