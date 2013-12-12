<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * http://twitter.github.com/bootstrap/base-css.html#forms
 * @package		Twitter Bootstrap
 * @category	Components
 * @author		ButscHSter
 */
class Bootstrap_Dropdown extends Bootstrap_Helper_Elements {
	
	const DIVIDER = 'divider';
	
	/**
	 * You can easily add dividers to your nav links with an empty list item 
	 * and a simple class. Just add this between links:
	 * 
	 *		<li class="divider"></li>
	 * 
	 * @return string
	 */
	public static function divider()
	{
		return '<li'.HTML::attributes(array('class' => Bootstrap_Dropdown::DIVIDER)).'></li>';
	}

	/**
	 * 
	 * @return string
	 */
	public static function caret()
	{	
		return '<span'.HTML::attributes(array('class' => 'caret')).'></span>';
	}
	
	protected $_template = 'dropdown/menu';
	
	public function default_attributes()
	{
		return array(
			'class' => 'dropdown-menu'
		);
	}
	
	/**
	 * 
	 * @param Bootstrap_Helper_Element $element
	 * @param integer $priority
	 */
	public function add( $element, $is_active = FALSE, array $attributes = array(), $priority = 0 )
	{
		$element_li = Bootstrap_Dropdown_Element::factory(array(
			'element' => $element
		))
			->attributes($attributes);
		
		if($is_active !== FALSE)
		{
			$element_li->attributes('class', 'active');
		}
		
		if( $element instanceof Bootstrap_Element_Button )
		{
			$element->attributes()->delete('class', '^btn');
		}
	
		if( $element instanceof Bootstrap_Dropdown )
		{
			$element_li->attributes('class', 'dropdown-submenu');
		}

		$this->_add($element_li, $priority);

		return $this;
	}
	
	public function icon( $icon_name )
	{
		if( ! empty($icon_name))
		{
			$title = $this->get('title');
			
			
			$this->set('title', 
				UI::icon( $icon_name ) . ' ' . $title);
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @return \Bootstrap_Dropdown
	 */
	public function add_divider()
	{
		$this->_elements[] = Bootstrap_Dropdown::divider();
		return $this;
	}

	protected function _build_content() 
	{
		parent::_build_content();

		$this->_template->set('elements', $this->_elements);
	}
}