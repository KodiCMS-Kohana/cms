<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * http://twitter.github.com/bootstrap/base-css.html#forms
 * @package		Twitter Bootstrap
 * @category	Form
 * @author		ButscHSter
 */
class Bootstrap_Form extends Bootstrap_Helper_Elements {
	
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
	 * Add .form-search to the form and .search-query to the <input> for an 
	 * extra-rounded text input.
	 */
	const SEARCH = 'form-search';
	
	/**
	 * Add .search-query to the <input> for an extra-rounded text input.
	 */
	const SEARCH_QUERY = 'search-query';
	
	const URLENCODED = 'application/x-www-form-urlencoded';
	const TEXT_PLAIN = 'text/plain';
	const MULTIPART = 'multipart/form-data';
	
	protected $_template = 'form/basic';
	
	/**
	 *
	 * @var Bootstrap_Helper_Elements
	 */
	protected $_buttons = NULL;


	public function default_attributes()
	{
		return array(
			'method' => Request::POST,
			'enctype' => Bootstrap_Form::URLENCODED
		);
	}
	
	public function __construct(array $data = array(), 
			array $attributes = array(), $template = NULL) 
	{
		parent::__construct($data, $attributes, $template);
		
		$this->_buttons = Bootstrap_Helper_Elements::factory()
			->set_parent( $this );
	}
	
	/**
	 * 
	 * @param Bootstrap_Form_Element_Button $button
	 * @return \Bootstrap_Form
	 */
	public function add_action( Bootstrap_Form_Element_Button $button )
	{
		$this->_buttons->add( $button );
		return $this;
	}

	protected function _build_content() 
	{
		$this
			->add( new Bootstrap_Form_Element_Hidden(array(
				'name' => 'csrf',
				'value' => Security::token()
			)) );

		$this->_template
			->set('buttons', $this->_buttons);

		parent::_build_content();
	}
}
