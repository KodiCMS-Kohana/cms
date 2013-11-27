<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		Twitter Bootstrap
 * @category	Helper
 * @author		ButscHSter
 */
class Bootstrap_Helper_Parameters extends Bootstrap_Helper_Abstract {
	
	/**
	 *
	 * @var array 
	 */
	protected $_required = array();
	
	/**
	 *
	 * @var Bootstrap_Abstract 
	 */
	protected $_object = NULL;

	/**
	 * 
	 * @param array $parameters
	 * @param array $required
	 */
	public function __construct( Bootstrap_Abstract $object, array $parameters = array(), array $required = array())
	{
		$this->_required = $required;
		$this->_object = $object;

		parent::__construct($parameters, ArrayObject::ARRAY_AS_PROPS);
		
		$this->check();
	}
	
	/**
	 * 
	 * @return \Bootstrap_Helper_Parameters
	 * @throws Bootstrap_Exception
	 */
	public function check()
	{
		$fields = array();
		foreach ($this->_required as $key )
		{
			if( $this->get($key) === NULL )
				$fields[] = $key;
		}
		
		if( !empty($fields))
		{
			throw new Bootstrap_Exception(
				'Parameters `:fields` in class - :class is required', array(
					':fields' => implode('`, `', $fields),
					':class' => get_class( $this->_object )
				));
		}
		
		return $this;
	}
}