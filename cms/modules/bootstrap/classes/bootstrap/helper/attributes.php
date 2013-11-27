<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		Twitter Bootstrap
 * @category	Helper
 * @author		ButscHSter
 */
class Bootstrap_Helper_Attributes extends Bootstrap_Helper_Abstract {
	
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
	public function __construct( Bootstrap_Abstract $object, array $attributes = array() )
	{
		$this->_object = $object;

		parent::__construct($attributes, ArrayObject::ARRAY_AS_PROPS);
		
		$this->set('class', array());
		
		if( $this->get('id') === NULL )
		{
			$this->set('id', $this->generate_id());
		}
	}

	/**
	 * 
	 * @return array
	 */
	public function as_array()
	{
		$array = array();
		foreach($this->getArrayCopy() as $key => $value)
		{
			if(is_array($value)) $value = implode(' ', $value);
			
			$array[$key] = $value;
		}

		return $array;
	}

	/**
	 * HTML::attributes($this->getArrayCopy())
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return HTML::attributes($this->as_array());
	}
	
	public function generate_id()
	{
		$class_name = strtolower( get_class($this->_object) );
		return  $class_name . '_' . Text::random();
	}
	
	/**
	 * @param   string  $key    array key
	 * @param   mixed   $value  array value
	 * @return  $this
	 */
	public function set($key, $value)
	{
		if( is_array($key) )
		{
			foreach ($key as $name => $value)
			{
				$this->set($name, $value);
			}
		}
		else if($key == 'class')
		{
			if( ! is_array( $value ) )
			{
				$value = explode(' ', $value);
			}
			
			$values = $this->offsetExists($key) 
					? $this->offsetGet($key) 
					: array();
	
			foreach ($value as $class)
			{
				$values[] = $class;
			}
			
			$this->offsetSet($key, $values);
		}
		else
		{
			$this->offsetSet($key, $value);
		}

		return $this;
	}
	
	/**
	 * 
	 * @param string $key
	 * @param string $value
	 * @return boolean
	 */
	public function has($key, $value)
	{
		$return = FALSE;
		
		if( is_array($this->get($key)))
		{
			$return = in_array($value, $this->get($key));
		}
		else
		{
			$return = ($this->get($key) == $value);
		}
		
		return $return;
	}

	/**
	 * 
	 * @param string $key
	 * @param string $value
	 * @return boolean
	 */
	public function delete($key, $value = NULL)
	{
		if( ! $this->offsetExists($key))
		{
			return FALSE;
		}
		
		if($value === NULL AND $this->offsetExists($key))
		{
			$this->offsetUnset($key);
			return TRUE;
		}
		
		if( is_array($this->offsetGet($key)) )
		{
			$data = $this->offsetGet($key);
			
			foreach($data as $i => $class)
			{
				if(preg_match('/'.$value.'/i', $class) > 0)
					unset($data[$i]);
			}

			$this->offsetSet($key, $data);
			
			return TRUE;
		}
		
		return FALSE;
	}
}