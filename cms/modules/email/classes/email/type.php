<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Email
 * @category	Helpers
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Email_Type
{
	/**
	 * 
	 * @param string $code
	 * @return Model_Email_Type
	 */
	public static function get($code)
	{
		return ORM::factory('email_type', array(
			'code' => (string) $code
		));
	}
}