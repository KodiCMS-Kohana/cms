<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class ORM extends Kohana_ORM {

	public function list_columns()
	{
		if ( Kohana::cache( 'table_columns_' . $this->_object_name ) )
		{
			return Kohana::cache( 'table_columns_' . $this->_object_name );
		}

		Kohana::cache( 'table_columns_' . $this->_object_name, $this->_db->list_columns( $this->table_name() ) );

		// Proxy to database
		return $this->_db->list_columns( $this->table_name() );
	}
}
