<?php

if ( !defined( 'CMS_ROOT' ) )
	die;

use_helper( 'flatfile' );

class Poll {

	const VOTE_DB = 'votes.txt';
	const IP_DB = 'ips.txt';

	// Column definitions
	const OPT_ID = 0;
	const OPT_TITLE = 1;
	const OPT_VOTES = 2;
	const OPT_PERCENTS = 3;

	protected $_options = array( );
	protected $_title = NULL;
	protected $_db = NULL;
	protected $_id = NULL;
	protected $_use_unique_ip = TRUE;

	public static function init( $poll_id )
	{
		return new Poll( $poll_id );
	}

	public function __construct( $poll_id )
	{
		$this->_db = new Flatfile();
		$this->_id = URL::title( $poll_id );

		$this->_set_poll_directory();
	}

	public function id()
	{
		return $this->_id;
	}

	public function title()
	{
		return $this->_title;
	}

	public function options()
	{
		return $this->_options;
	}

	public function add_title( $title )
	{
		$this->_title = $title;
		return $this;
	}

	public function add_option( $id, $title )
	{
		$this->_options[$id] = $title;
		return $this;
	}

	public function add_vote( $id )
	{
		if ( $this->_use_unique_ip === TRUE )
		{
			if ( !$this->_is_unique_ip() )
			{
				return $this->get_results();
			}
		}
		else if ( $this->_is_unique_ip() )
		{
			$this->_insert_ip();
		}

		$row = $this->_db->selectUnique( self::VOTE_DB, self::OPT_ID, $id );

		if ( !empty( $row ) )
		{
			$new_votes = $row[self::OPT_VOTES] + 1;
			$this->_db->updateSetWhere( self::VOTE_DB, array( self::OPT_VOTES => $new_votes ), new SimpleWhereClause( self::OPT_ID, '=', $id ) );
		}
		else if ( isset( $this->_options[$id] ) )
		{
			$new_row[self::OPT_ID] = $id;
			$new_row[self::OPT_TITLE] = $this->_options[$id];
			$new_row[self::OPT_VOTES] = 1;
			$this->_db->insert( self::VOTE_DB, $new_row );
		}

		return $this->get_results();
	}

	public function only_unique_ip( $status = NULL )
	{
		if($status === NULL)
		{
			return $this->_use_unique_ip;
		}

		$this->_use_unique_ip = (bool) $status;
		return $this;
	}

	public function get_results()
	{
		$rows = $this->_db->selectWhere( self::VOTE_DB, new SimpleWhereClause( self::OPT_ID, "!=", 0 ), -1, new OrderBy( self::OPT_VOTES, DESCENDING, INTEGER_COMPARISON ) );

		$total_votes = 0;
		foreach ( $rows as $row )
		{
			$total_votes = $row[self::OPT_VOTES] + $total_votes;
		}

		foreach ( $rows as & $row )
		{
			$percent = round( ($row[self::OPT_VOTES] / $total_votes) * 100 );

			$row[self::OPT_PERCENTS] = $percent;
		}

		return array( $total_votes, $rows );
	}
	
	public function is_voted()
	{
		return ! $this->_is_unique_ip();
	}

	protected function _is_unique_ip( $ip = NULL )
	{
		if ( $ip === NULL )
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$ips = $this->_db->selectUnique( self::IP_DB, 0, $ip );

		return empty( $ips );
	}

	protected function _insert_ip( $ip = NULL )
	{
		if ( $ip === NULL )
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$ips = array( );

		$ips[0] = $ip;
		$this->_db->insert( self::IP_DB, $ips );
	}

	protected function _set_poll_directory()
	{
		$plugin_path = PLUGINS_ROOT . DIRECTORY_SEPARATOR . 'poll' . DIRECTORY_SEPARATOR;
		$directory = $plugin_path . 'data' . DIRECTORY_SEPARATOR . $this->_id . DIRECTORY_SEPARATOR;

		if ( !is_dir( $directory ) )
		{
			mkdir( $directory, 02777 );
			chmod( $directory, 02777 );
		}
		
		if ( ! file_exists($directory . self::VOTE_DB))
		{
			file_put_contents($directory . self::VOTE_DB, '');
			chmod($filename, 0666);
		}
		
		if ( ! file_exists($directory . self::IP_DB))
		{
			file_put_contents($directory . self::IP_DB, '');
			chmod($filename, 0666);
		}

		$this->_db->datadir = $directory;
	}
}