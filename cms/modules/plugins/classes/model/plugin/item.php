<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Plugin_Item {

	protected $_initialized = FALSE;
	protected $_data = array(
		'id' => NULL,
		'title' => NULL,
		'description' => NULL,
		'version' => '0.0.0',
		'settings' => FALSE
	);
	protected $_javascripts = array();
	protected $_styles = array();
	protected $_settings = array();
	protected $_attributes = array();

	public static function factory( array $data )
	{
		return new self( $data );
	}

	public function __construct( $data = array() )
	{
		$this->data( $data );
	}

	public function __set( $name, $value )
	{
		if ( isset( $this->_data[$name] ) )
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

	public function __get( $name )
	{
		return $this->get( $name );
	}
	
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

	public function data( array $data )
	{
		foreach ( $data as $key => $value )
		{
			$this->$key = $value;
		}

		return $this;
	}
	
	public function get_settings( )
	{
		return $this->_settings;
	}

	public function enabled()
	{
		return Model_Plugin::is_enabled( $this->id );
	}

	public function register()
	{
		Model_Plugin::register( $this );

		foreach ( $this->_javascripts as $file )
		{
			Model_Plugin::add_javascript( $this->id, $file );
		}

		foreach ( $this->_styles as $file )
		{
			Model_Plugin::add_style( $this->id, $file );
		}
		
		$this->init();

		return $this;
	}
	
	public function javascripts()
	{
		return $this->_javascripts;
	}

	public function styles()
	{
		return $this->_styles;
	}

	public function init()
	{
		if($this->enabled())
		{
			$this->_settings = Model_Plugin::get_settings( $this->id );
		}
	}

}