<?php

if ( !defined( 'CMS_ROOT' ) )
	die;

class Polls {

	protected $_polls = array( );
	protected static $instances;

	public function __construct()
	{
		
	}

	/**
	 * @return  Polls
	 */
	public function add_poll( Poll $poll )
	{
		$this->_polls[$poll->id()] = $poll;

		return $this;
	}
	
	/**
	 * @return  array
	 */
	public function get_all( )
	{
		return $this->_polls;
	}

	/**
	 * @return  Poll
	 */
	public function get_poll( $id )
	{
		return Arr::get($this->_polls, $id);
	}

	/**
	 * @return  Polls
	 */
	public static function instance()
	{
		if ( !isset( self::$instances ) )
		{
			self::$instances = new Polls;
		}

		return self::$instances;
	}

}