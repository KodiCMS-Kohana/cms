<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi/Api
 */
class Model_API extends Model_Database {
	
	/**
	 * Table columns
	 * @var array
	 */
	protected $_table_columns;
	
	/**
	 * Secured table columns
	 * @var array
	 */
	protected $_secured_columns = array();
	
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name;
	
	public function __construct( $db = NULL )
	{
		parent::__construct( $db );
		
		$this->_table_columns = $this->list_columns();
	}
	
	/**
	 * @return string
	 */
	public function table_name()
	{
		return $this->_table_name;
	}
	
	/**
	 * @return array
	 */
	public function table_columns()
	{
		return $this->_table_columns;
	}
	
	/**
	 * @return array
	 */
	public function secured_columns()
	{
		return $this->_secured_columns;
	}

	/**
	 * 
	 * @global type $table_name
	 * @param array $fields
	 * @param array $related_columns
	 * @param array $remove_fields
	 * @return array
	 * @throws HTTP_API_Exception
	 */
	public function filtered_fields(array $fields, $related_columns = array(), $remove_fields = array())
	{
		$secured_fields = array_intersect($this->_secured_columns, $fields);

		// Exclude fields
		$fields = array_diff($fields, $remove_fields);
		
		// TODO сделать проверку токена, выдаваемого под API
		if( ! empty($secured_fields) AND ! AuthUser::isLoggedIn('login') )
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You don`t have permissions to access to this fields (:fields).', array(
				':fields' => implode(', ', $secured_fields)
			));
		}

		$fields = array_intersect(array_keys($this->_table_columns), $fields);
		
		foreach ($fields as $i => $field)
		{
			$fields[$i] = $this->table_name() . '.' . $field;
		}

		return array_intersect($related_columns, $fields);
	}
	
	/**
	 * 
	 * @param mixed $param
	 * @param mixed $filter
	 * @return array
	 */
	public function prepare_param($param, $filter = NULL)
	{
		if(!is_array($param))
		{
			$param = explode(',', $param);
		}
		
		if($filter !== NULL)
		{
			$param = array_filter($param, $filter);
		}

		return $param;
	}

	/**
	 * Proxy method to Database list_columns.
	 *
	 * @return array
	 */
	public function list_columns()
	{
		if ( Kohana::cache( 'table_columns_' . $this->_table_name ) )
		{
			return Kohana::cache( 'table_columns_' . $this->_table_name );
		}

		Kohana::cache( 'table_columns_' . $this->_table_name, $this->_db->list_columns( $this->_table_name ) );

		// Proxy to database
		return $this->_db->list_columns( $this->_table_name );
	}
}