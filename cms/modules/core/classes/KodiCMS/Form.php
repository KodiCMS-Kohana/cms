<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package		KodiCMS
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Form extends Kohana_Form {

	/**
	 * Add .form-inline for left-aligned labels and inline-block controls 
	 * for a compact layout.
	 */
	const INLINE = 'form-inline';

	/**
	 * Right align labels and float them to the left to make them appear on 
	 * the same line as controls. Requires the most markup changes 
	 * from a default form:
	 */
	const HORIZONTAL = 'form-horizontal';

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