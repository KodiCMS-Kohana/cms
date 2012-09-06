<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Messages {

	protected static $_errors = array();
	protected static $_success = array();
	
	// Message types
	const SUCCESS = 'success';
	const ERRORS = 'errors';
	
	/**
	 * @var  string  default session key used for storing messages
	 */
	public static $session_key = 'message';

	public static function get( $type = NULL )
	{
		if ( $type === NULL )
		{
			$array = array();

			$success = Session::instance()
				->get_once( self::$session_key.'_'.Messages::SUCCESS, array() );

			$errors = Session::instance()
				->get_once( self::$session_key.'_'.Messages::ERRORS, array() );

			if ( !empty( $success ) )
			{
				$array[Messages::SUCCESS] = $success;
			}

			if ( !empty( $errors ) )
			{
				$array[Messages::ERRORS] = $errors;
			}

			return $array;
		}

		return Session::instance()->get_once( $type, array() );
	}

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

		if ( $type == Messages::SUCCESS )
		{
			self::$_success = Arr::merge( self::$_success, $data );
			Session::instance()
				->set( self::$session_key.'_'.Messages::SUCCESS, self::$_success );
		}
		else
		{
			self::$_errors = Arr::merge( self::$_errors, $data );
			Session::instance()
				->set( self::$session_key.'_'.Messages::ERRORS, self::$_errors );
		}
	}

	public static function errors( $data = NULL, $values = NULL )
	{
		if ( $data === NULL )
		{
			return self::get( Messages::ERRORS );
		}

		return Messages::set( Messages::ERRORS, $data, $values );
	}

	public static function success( $data = NULL, $values = NULL )
	{
		if ( $data === NULL )
		{
			return self::get( Messages::SUCCESS );
		}

		return Messages::set( Messages::SUCCESS, $data, $values );
	}

}