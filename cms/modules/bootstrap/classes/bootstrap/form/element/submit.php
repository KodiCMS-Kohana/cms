<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * http://twitter.github.com/bootstrap/base-css.html#forms
 * @package		Twitter Bootstrap
 * @category	Form
 * @author		ButscHSter
 */
class Bootstrap_Form_Element_Submit extends Bootstrap_Form_Element_Button {
	
	protected function _build_content() 
	{
		parent::_build_content();
		
		$this->_content = Form::submit($this->get('name'), 
				$this->get('title'), $this->attributes()->as_array());
	}
	
}