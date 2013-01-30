<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class ORM extends Kohana_ORM {

	public function list_columns()
	{
		$cache = Cache::instance();
		if ( ($result = $cache->get( 'table_columns_' . $this->_object_name )) !== NULL )
		{
			return $result;
		}

		$cache->set( 'table_columns_' . $this->_object_name, $this->_db->list_columns( $this->table_name() ) );

		// Proxy to database
		return $this->_db->list_columns( $this->table_name() );
	}
}
