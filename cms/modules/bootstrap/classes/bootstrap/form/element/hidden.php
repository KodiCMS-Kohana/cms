<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * http://twitter.github.com/bootstrap/base-css.html#forms
 * @package		Twitter Bootstrap
 * @category	Form
 * @author		ButscHSter
 */
class Bootstrap_Form_Element_Hidden extends Bootstrap_Form_Helper_Elements {
	
	public function required()
	{
		return array('name');
	}
	
	protected function _build_content() 
	{
		parent::_build_content();
		
		$this->_content = Form::hidden($this->get('name'), $this->get('value'));
	}
}