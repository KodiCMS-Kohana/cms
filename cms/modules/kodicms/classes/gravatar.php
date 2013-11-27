<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		ButscHSter
 */
class Gravatar {
	
	public static function load($email, $size = 40, $default = NULL, $attributes = array())
	{
		if(empty($email)) return NULL;

		if($default === NULL)
		{
			$default = 'mm';
		}
	
		$hash = md5( strtolower( trim( $email ) ) );
		$query_params = URL::query(array(
			'd' => $default,
			's' => $size
		));
		
		return HTML::image('http://www.gravatar.com/avatar/' . $hash . $query_params, $attributes);
	}
}