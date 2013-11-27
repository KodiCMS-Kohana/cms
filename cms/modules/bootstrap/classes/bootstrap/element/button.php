<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * http://twitter.github.com/bootstrap/base-css.html#forms
 * @package		Twitter Bootstrap
 * @author		ButscHSter
 */
class Bootstrap_Element_Button extends Bootstrap_Helper_Elements {
	
	const BTN		= 'btn';
	
	/**
	 * Default buttons
	 */
	const PRIMARY	= 'btn-primary';
	const SUCCESS	= 'btn-success';
	const WARNING	= 'btn-warning';
	const DANGER	= 'btn-danger';
	const INFO		= 'btn-info';
	const INVERSE	= 'btn-inverse';
	const LINK		= 'btn-link';
	
	/**
	 * Button sizes
	 */
	const LARGE		= 'btn-large';
	const MINI		= 'btn-mini';
	const SMALL		= 'btn-small';
	
	/**
	 * Block level button
	 */
	const BLOCK		= 'btn-block';
	
	/**
	 * Disabled state
	 */
	const DISABLED	= 'disabled';
	
	protected $_template = 'element/button';


	public function required()
	{
		return array('href', 'title');
	}
	
	public function default_attributes()
	{
		return array(
			'class' => Bootstrap_Element_Button::BTN
		);
	}
	
	/**
	 * 
	 * @param string $icon_name
	 * @return Bootstrap_Element_Button
	 */
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
	 * @param boolean $status
	 * @return Bootstrap_Element_Button
	 */
	public function disabled()
	{
		return $this->attributes('class', 'disabled');
	}
	
	/**
	 * 
	 * @param atring $size
	 * @param boolean $block_level
	 * @return \Bootstrap_Element_Button
	 */
	public function size( $size, $block_level = FALSE )
	{
		if(in_array( $size, array(
			Bootstrap_Element_Button::SMALL, 
			Bootstrap_Element_Button::MINI, 
			Bootstrap_Element_Button::LARGE
			)))
		{
			$this->attributes('class', $size);
		}
		
		if( $block_level !== FALSE ) 
		{
			$this->block_level();
		}

		return $this;
	}
	
	/**
	 * 
	 * @return \Bootstrap_Element_Button
	 */
	public function block_level()
	{
		return $this->attributes('class', Bootstrap_Element_Button::BLOCK);
	}

		/**
	 * 
	 * @param string $type
	 * @return \Bootstrap_Element_Button
	 */
	public function type( $type )
	{
		if(in_array( $type, array(
			Bootstrap_Element_Button::PRIMARY,
			Bootstrap_Element_Button::INFO, 
			Bootstrap_Element_Button::SUCCESS, 
			Bootstrap_Element_Button::WARNING, 
			Bootstrap_Element_Button::DANGER, 
			Bootstrap_Element_Button::INVERSE, 
			Bootstrap_Element_Button::LINK
		)))
		{
			$this->attributes('class', $type);
		}
		
		return $this;
	}

	protected function _build_content() 
	{
		parent::_build_content();

		$this->_content = HTML::anchor($this->get('href'), 
				$this->get('title'), $this->attributes()->as_array());
	}
}