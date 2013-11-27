<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * http://twitter.github.com/bootstrap/base-css.html#forms
 * @package		Twitter Bootstrap
 * @category	Form
 * @author		ButscHSter
 */
class Bootstrap_Form_Element_Radio extends Bootstrap_Form_Element_Checkbox {
	
	/**
	 * 
	 * @param string $title
	 * @param boolean $inline
	 * @param array $attributes
	 * @return \Bootstrap_Abstract
	 */
	public function label( $title, $inline = FALSE, array $attributes = array() )
	{
		$label = Bootstrap_Form_Element_Label::factory(array(
			'for' => $this->attributes('id'), 
			'title' => $title), $attributes)
			->attributes('class', 'radio')
			->set_parent( $this );
		
		if($inline !== FALSE)
		{
			$label->attributes('class', 'inline');
		}
		
	
		return $this->set('label', $label);
	}
	
	protected function _build_content() 
	{
		parent::_build_content();
		
		$this->_content = Form::radio($this->get('name'), $this->get('value'), $this->get('checked'), $this->attributes()->as_array());
	}
}