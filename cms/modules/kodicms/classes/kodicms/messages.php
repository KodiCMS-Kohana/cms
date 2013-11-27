<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		ButscHSter
 */
class KodiCMS_Messages {

	protected static $_data = array();
	
	// Message types
	const SUCCESS = 'success';
	const ERRORS = 'errors';
	
	/**
	 * @var  string  default session key used for storing messages
	 */
	public static $session_key = 'message';
	
	/**
	 * 
	 * @return array
	 */
	public static function types()
	{
		return array(
			Messages::SUCCESS, Messages::ERRORS
		);
	}

	/**
	 * 
	 * @param string $type
	 * @return array
	 */
	public static function get( $type = NULL )
	{
		$session = Session::instance();
		if ( $type === NULL )
		{
			$array = array();

			foreach (self::types() as $i => $type)
			{
				$array[$type] = $session->get_once( self::$session_key.'::'.$type, array() );
			}

			return $array;
		}

		return $session->get_once( self::$session_key.'::'.$type, array() );
	}

	/**
	 * 
	 * @param string $type
	 * @param mixed $data
	 * @param array $values
	 */
	public static function set( $type = Messages::SUCCESS, $data = NULL, $values = NULL )
	{
		if ( !is_array( $data ) )
		{
			$data = array($data);
		}

		foreach ( $data as $index => $string )
		{
			$data[$index] = empty( $values ) ? $string : strtr( $string, $values );
		}


		self::$_data[$type] = !empty(self::$_data[$type]) 
			? Arr::merge( self::$_data[$type], $data )
			: $data;

		Session::instance()
			->set( self::$session_key.'::'.$type, self::$_data[$type] );
	}

	/**
	 * 
	 * @param string $data
	 * @param array $values
	 * @return void
	 */
	public static function errors( $data = NULL, $values = NULL )
	{
		if ( $data === NULL )
		{
			return self::get( Messages::ERRORS );
		}

		return Messages::set( Messages::ERRORS, $data, $values );
	}

	/**
	 * 
	 * @param string $data
	 * @param array $values
	 * @return void
	 */
	public static function success( $data = NULL, $values = NULL )
	{
		if ( $data === NULL )
		{
			return self::get( Messages::SUCCESS );
		}

		return Messages::set( Messages::SUCCESS, $data, $values );
	}
	
	public static function validation(Validation $validation, $file = 'validation')
	{
		$errors = $validation->errors($file);
		return Messages::errors($errors);
	}

}