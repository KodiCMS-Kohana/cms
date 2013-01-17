<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi/Api
 */
class Model_API_Message extends Model_API {
	
	protected $_table_name = 'messages';
	
	protected $_secured_columns = array(
		'text'
	);

	
	public function get($user_id, $parent_id, $fields)
	{
		$user_id = $this->prepare_param($user_id, array('Valid', 'numeric'));
		$parent_id = $this->prepare_param($parent_id, array('Valid', 'numeric'));
		$fields = $this->prepare_param($fields);
		
		$min_status = DB::select(DB::expr('MIN('.Database::instance()->quote_column('status').')'))
			->from(array('messages_users', 'ms'))
			->where('ms.parent_id', '=', DB::expr(Database::instance()->quote_column('messages_users.message_id')))
			->or_where('ms.message_id', '=', DB::expr(Database::instance()->quote_column('messages_users.message_id')));
		
		return DB::select('messages_users.status')
			->select_array( $this->filtered_fields( $fields ) )
			->select(array($min_status, 'status'))
			->from($this->_table_name)
			->join('messages_users', 'left')
				->on($this->_table_name.'.id', '=', 'messages_users.message_id')
			->select( array('users.name', 'author') )
			->join( 'users', 'left' )
				->on( 'users.id', '=', $this->_table_name . '.from_user_id' )
			->where('messages_users.user_id', '=', $user_id)
			->where('messages_users.parent_id', '=', $parent_id)
			->order_by('status', 'asc')
			->order_by('created_on', 'desc')
			->as_object('Model_Message')
			->execute();
	}
}