<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_Search_Buffer {

	var $data;
	var $_length, $_chars;

	// Default and single constructor
	function __construct( $string )
	{
		$this->setString( $string );
	}

	// Sets specified length
	function setLength( $length )
	{
		if ( $length != $this->getLength() )
		{
			if ( $length < $this->getLength() )
				$this->setString( UTF8::substr( $this->data, 0, $length ) );
			else
				$this->setString( $this->data . str_repeat( ' ', $length - $this->getLength() ) );
		}
	}

	// Gets $data's length
	function getLength()
	{
		return $this->_length;
	}

	// Gets char at specified position
	function charAt( $index )
	{
		return UTF8::substr( $this->data, $index, 1 );
	}

	// Gets charcode at specified position
	function charCodeAt( $index )
	{
		return $this->_chars[$index];
	}

	// Gets string
	function getString()
	{
		return $this->data;
	}

	// Sets string
	function setString( $string )
	{
		$this->data = $string;
		$this->_chars = UTF8::to_unicode( $this->data );
		$this->_length = sizeof( $this->_chars );
	}

}