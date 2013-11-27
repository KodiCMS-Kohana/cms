<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * http://twitter.github.com/bootstrap/base-css.html#forms
 * @package		Twitter Bootstrap
 * @category	Form
 * @author		ButscHSter
 */
class Bootstrap_Form_Inline extends Bootstrap_Form {
	
	public function default_attributes()
	{
		$array = parent::default_attributes();
		
		$array['class'] = Bootstrap_Form::INLINE;
		return $array;
	}
}