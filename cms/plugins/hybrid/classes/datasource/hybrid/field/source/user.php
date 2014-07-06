<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Source_User extends DataSource_Hybrid_Field_Source {
	
	protected $_props = array(
		'default' => NULL,
		'isreq' => FALSE,
		'only_current' => FALSE,
		'unique' => FALSE
	);
	
	protected $_use_as_document_id = TRUE;

	public function booleans()
	{
		return array('only_current', 'unique', 'set_current');
	}
	
	public function set( array $data )
	{
		return parent::set( $data );
	}
	
	public function onCreateDocument(DataSource_Hybrid_Document $doc) 
	{
		return $this->onUpdateDocument($doc, $doc);
	}
	
	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new)
	{
		$user_id = $new->get($this->name);

		if($this->only_current === TRUE)
		{
			$user_id = $old->get($this->name);
		}
		
		if( ! $this->is_exists( $user_id ))
		{
			$user_id = 0;
		}
		
		$new->set($this->name, $user_id);
	}
	
	public function get_user($id)
	{
		return ORM::factory('user', $id);
	}
	
	public function is_exists($id)
	{
		return $this->get_user($id)->loaded();
	}
	
	public function get_users()
	{
		$users = array('--------');
		$users = $users + ORM::factory('user')->find_all()->as_array('id', 'username');
		
		return $users;
	}
	
	public function fetch_headline_value( $value )
	{
		if(empty($value)) return parent::fetch_headline_value($value);

		$user = ORM::factory('user', (int) $value);
		
		if( ! $user->loaded())
		{
			return parent::fetch_headline_value($value);
		}

		$header = DataSource_Hybrid_Field_Utils::get_document_header($this->from_ds, $value);
		
		return HTML::anchor(Route::get('backend')->uri(array(
			'controller' => 'users',
			'action' => 'profile',
			'id' => $user->id
		)), $user->username, array(
			'class' => ' popup fancybox.iframe'
		));
	}

	public static function fetch_widget_field( $widget, $field, $row, $fid )
	{
		return !empty($row[$fid]) 
			? array(
				'username' => $row[$fid],
				'id' => $row['user_id']
			)
			: array(
				'username' => '',
				'id' => ''
			);
	}
	
	public function get_type()
	{
		return 'TINYINT(4)';
	}
	
	public function get_query_props(\Database_Query $query, DataSource_Hybrid_Agent $agent)
	{
		return $query->join('users', 'left')
			->on(DataSource_Hybrid_Field::PREFFIX . $this->key, '=', 'users' . '.id')
			->select(array('users.username', $this->id))
			->select(array('users.id', 'user_id'));
	}
}