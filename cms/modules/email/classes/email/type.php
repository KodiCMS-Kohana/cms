<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Email
 * @category	Helpers
 * @author		ButscHSter
 */
class Email_Type
{
	/**
	 * 
	 * @param string $code
	 * @return Model_Email_Type
	 */
	public static function get( $code )
	{
		return ORM::factory('email_type', array(
			'code' => (string) $code
		));
	}
}