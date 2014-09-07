<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package		KodiCMS
 * @category	Form
 * @author		ButscHSter
 */
class KodiCMS_Form extends Kohana_Form {

	/**
	 * 
	 * @return array
	 */
	public static function choices()
	{
		return array(
			Config::NO => __('No'),
			Config::YES => __('Yes')
		);
	}
	
	/**
	 * Creates a token form input.
	 *
	 *     echo Form::token('csrf');
	 *
	 * @param   string  $name       input name
	 * @param   string  $value      input value
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    Form::input
	 */
	public static function token($name = 'csrf')
	{
		return Form::hidden($name, Security::token());
	}
}