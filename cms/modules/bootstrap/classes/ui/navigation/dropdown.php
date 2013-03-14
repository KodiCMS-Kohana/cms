<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * http://twitter.github.com/bootstrap/components.html#navbar
 * @package    Twitter bootstrap/UI
 */
class UI_Navigation_Dropdown {
	
	const DIVIDER = 'divider';

	public static function caret()
	{	
		return '<b'.HTML::attributes(array('class' => 'caret')).'></b>';
	}
	
	public static function link( $title )
	{
		return HTML::anchor('#', $title . UI_Navigation_Dropdown::caret(), array(
			'data-toggle' => 'dropdown',
			'class' => 'dropdown-toggle'
		));
	}
	
	public static function divider()
	{
		return '<li'.HTML::attributes(array('class' => UI_Navigation_Dropdown::DIVIDER)).'></li>';
	}

	public static function factory( array $attributes = NULL, $template = NULL )
	{
		return new UI_Navigation_Dropdown( $attributes, $template );
	}
	
	/**
	 *
	 * @var array 
	 */
	protected $_attributes = array();
	
	/**
	 *
	 * @var View 
	 */
	protected $_template = 'bootstrap/navigation/dropdown';
	
	/**
	 *
	 * @var array 
	 */
	protected $_items = array();
	
	/**
	 * 
	 * @param array $attributes
	 * @param string $template
	 */
	public function __construct( array $attributes = NULL, $template = NULL ) 
	{
		if( empty($attributes['class']))
			$attributes = array('class' => 'dropdown-menu');
		else
			$attributes['class'] .= ' dropdown-menu';

		$this->_attributes = $attributes;

		if( $template !== NULL )
		{
			$this->_template = $template;
		}
		
		$this->_template = View::factory($this->_template);
	}
	
	/**
	 * 
	 * @param string $data
	 * @param boolean $is_active
	 * @param array $attributes
	 * @return \UI_Navigation_Dropdown
	 */
	public function add_item( $data = NULL, $is_active = FALSE, array $attributes = NULL )
	{
		if( $is_active !== FALSE)
		{
			if( empty($attributes['class']))
				$attributes['class'] = 'active';
			else
				$attributes['class'] .= ' active';
		}

		$this->_items[] = array(
			'data' => $data, 
			'attributes' => HTML::attributes($attributes)
		);
		
		return $this;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function __toString() 
	{
		return (string) $this->render();
	}

	/**
	 * 
	 * @param string $template
	 * @return string
	 */
	public function render( $template = NULL )
	{		
		return $this->_template
			->set('attributes', HTML::attributes( $this->_attributes ))
			->set('items', $this->_items)
			->render( $template );
	}
}