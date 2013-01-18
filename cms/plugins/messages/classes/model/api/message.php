<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi/Api
 */
class Model_API_Message extends Model_API {
	
	protected $_table_name = 'messages';
	
	protected $_secured_columns = array(
		'text'
	);
	
	public function get_all($user_id, $parent_id, $fields)
	{
		$user_id = $this->prepare_param($user_id, array('Valid', 'numeric'));
		$parent_id = $this->prepare_param($parent_id, array('Valid', 'numeric'));
		$fields = $this->prepare_param($fields);

		$query = DB::select($this->_table_name . '.id')
			->select_array(  $this->filtered_fields( $fields ) )
			->from($this->_table_name)
			->join('messages_users', 'left')
				->on($this->_table_name.'.id', '=', 'messages_users.message_id')
			->where('messages_users.user_id', '=', $user_id)
			->where('messages_users.parent_id', '=', $parent_id)
			->order_by('created_on', 'desc');
		
		if(in_array('author', $fields))
		{
			$query->join( 'users', 'left' )
				->select( array('users.name', 'author') )
				->on( 'users.id', '=', $this->_table_name . '.from_user_id' );
		}
		
		if(in_array('status', $fields))
		{
			$query->select( 'messages_users.status' );
		}
		
		if(in_array('is_read', $fields))
		{
			$min_status = DB::select(DB::expr('MIN('.Database::instance()->quote_column('status').')'))
				->from(array('messages_users', 'ms'))
				->where('ms.parent_id', '=', DB::expr(Database::instance()->quote_column('messages_users.message_id')))
				->or_where('ms.message_id', '=', DB::expr(Database::instance()->quote_column('messages_users.message_id')));
			
			$query->select(array($min_status, 'is_read'))
				->order_by('is_read', 'asc');
		}

		$query->limit( (int) $this->get('limit', 10) );
		
		if( isset($this->offset) )
		{
			$query->offset( (int) $this->offset );
		}
		
		return $query->execute()->as_array();
	}
	
	public function get_by_id($user_id, $fields)
	{
		$user_id = $this->prepare_param($user_id, array('Valid', 'numeric'));
		$fields = $this->prepare_param($fields);
		
		$query = DB::select()
			->select('messages_users.status')
			->join('messages_users', 'left')
				->on($this->object_name().'.id', '=', 'messages_users.message_id')
			->select( array('users.name', 'author') )
			->join( 'users', 'left' )
				->on( 'users.id', '=', 'from_user_id' )
			->where('messages_users.user_id', '=', (int) $user)
			->where($this->object_name().'.id', '=', (int) $id)
			->find();
	}
}