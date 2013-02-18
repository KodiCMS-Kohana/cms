<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package    Plugins
 */

class Plugins_Item {

	/**
	 *
	 * @var boolean
	 */
	protected $_initialized = FALSE;
	
	/**
	 *
	 * @var array
	 */
	protected $_data = array(
		'id' => NULL,
		'title' => NULL,
		'description' => NULL,
		'version' => '0.0.0',
		'settings' => FALSE,
		'iframe' => TRUE
	);

	/**
	 *
	 * @var array
	 */
	protected $_javascripts = array();
	
	/**
	 *
	 * @var array
	 */
	protected $_styles = array();
	
	/**
	 *
	 * @var array
	 */
	protected $_settings = array();
	
	/**
	 *
	 * @var array
	 */
	protected $_attributes = array();

	/**
	 * 
	 * @param array $data
	 * @return Plugins_Item
	 */
	public static function factory( array $data )
	{
		return new self( $data );
	}

	/**
	 * 
	 * @param array $data
	 */
	public function __construct( $data = array() )
	{
		$this->data( $data );
	}

	/**
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set( $name, $value )
	{
		if ( array_key_exists( $name, $this->_data ) )
		{
			if ( $name == 'id' AND $value != NULL )
			{
				$this->_initialized = TRUE;
			}

			$this->_data[$name] = $value;
		}
		elseif ( $name == 'javascripts' )
		{
			if ( is_array( $value ) )
			{
				$this->_javascripts = $value;
			}
			else
			{
				$this->_javascripts[] = $value;
			}
		}
		elseif ( $name == 'css' )
		{
			if ( is_array( $value ) )
			{
				$this->_styles = $value;
			}
			else
			{
				$this->_styles[] = $value;
			}
		}
		else
		{
			$this->_attributes[$name] = $value;
		}
	}

	/**
	 * 
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		return $this->get( $name );
	}
	
	/**
	 * 
	 * @param string $name
	 * @param mixed $default
	 * @return midex|$default
	 */
	public function get( $name, $default = NULL )
	{
		if ( isset( $this->_data[$name] ) )
		{
			return $this->_data[$name];
		}

		if ( isset($this->_settings[$name]) )
		{
			return $this->_settings[$name];
		}
		elseif ( isset( $this->_attributes[$name] ) )
		{
			return $this->_attributes[$name];
		}

		return $default;
	}

	/**
	 * 
	 * @param array $data
	 * @return \Plugins_Item
	 */
	public function data( array $data = array() )
	{
		foreach ( $data as $key => $value )
		{
			$this->$key = $value;
		}

		return $this;
	}
	
	public function as_array()
	{
		return $this->_data;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function get_settings( )
	{
		return $this->_settings;
	}

	/**
	 * 
	 * @return boolean
	 */
	public function enabled()
	{
		return Plugins::is_enabled( $this->id );
	}

	/**
	 * 
	 * @return \Plugins_Item
	 */
	public function register()
	{
		if( ! Plugins::register( $this ) )
		{
			return $this;
		}

		foreach ( $this->_javascripts as $file )
		{
			Plugins::add_javascript( $this->id, $file );
		}

		foreach ( $this->_styles as $file )
		{
			Plugins::add_style( $this->id, $file );
		}
		
		$this->init();

		return $this;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function javascripts()
	{
		return $this->_javascripts;
	}

	/**
	 * 
	 * @return array
	 */
	public function styles()
	{
		return $this->_styles;
	}

	/**
	 * 
	 * @return \Plugins_Item
	 */
	public function init()
	{
		if($this->enabled())
		{
			$this->_settings = Plugins_Settings::get_settings( $this->id );
		}
		
		return $this;
	}

}