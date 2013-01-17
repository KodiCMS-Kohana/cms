<?php defined('SYSPATH') or die('No direct script access.');

class Model_Message extends ORM {
	
	const STATUS_READ	= 1; // Сообщение прочитано
	const STATUS_NEW	= 0; // Новое сообщение	

	protected $_created_column = array(
		'column' => 'created_on',
		'format' => 'Y-m-d H:i:s'
	);
	
	protected $_sorting = array(
		'created_on' => 'desc'
	);
	
	protected $_has_many = array(
		'users' => array(
			'model' => 'user', 
			'foreign_key' => 'user_id'
		),
	);
	
	protected $_reload_on_wakeup = FALSE;
	
	protected $_belongs_to = array(
		'from' => array('model' => 'user', 'foreign_key' => 'from_user_id'),
	);
	
	public function is_read()
	{
		return $this->status == self::STATUS_READ;
	}
	
	public function created()
	{
		return Date::format($this->created_on, 'd F Y H:i:s');
	}
	
	public function viewed()
	{
		return Date::format($this->updated_on);
	}
	
	public function get_one($id, $user)
	{
		return $this
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

	public function get_all($user, $parent_id = 0)
	{
		$min_status = DB::select(DB::expr('MIN('.Database::instance()->quote_column('status').')'))
			->from(array('messages_users', 'ms'))
			->where('ms.parent_id', '=', DB::expr(Database::instance()->quote_column('messages_users.message_id')))
			->or_where('ms.message_id', '=', DB::expr(Database::instance()->quote_column('messages_users.message_id')));
		
		return DB::select($this->table_name().'.*', 'messages_users.status')
			->select(array($min_status, 'status'))
			->from($this->table_name())
			->join('messages_users', 'left')
				->on($this->table_name().'.id', '=', 'messages_users.message_id')
			->select( array('users.name', 'author') )
			->join( 'users', 'left' )
				->on( 'users.id', '=', $this->table_name() . '.from_user_id' )
			->where('messages_users.user_id', '=', (int) $user)
			->where('messages_users.parent_id', '=', (int) $parent_id)
			->order_by('status', 'asc')
			->order_by('created_on', 'desc')
			->as_object('Model_Message')
			->execute($this->_db);
	}
	
	public function get_users_by_id($message_id)
	{
		return DB::select('user_id')
			->from('messages_users')
			->where('messages_users.message_id', '=', (int) $message_id)
			->execute($this->_db)
			->as_array(NULL, 'user_id');
	}

	public function send($title = '', $text, $from = NULL, $to, $parent_id = 0, $to_from = TRUE)
	{
		if (!is_array($to)) 
		{
			$to = array($to);
		}
		
		if(!$from)
		{
			$from = NULL;
		}
		elseif($from instanceof Model_User)
		{
			$from = $from->id;
		}
		
		if(!empty($to)) 
		{
			if($from !== NULL AND $to_from === TRUE) $to[] = $from;
			$to = array_unique($to);

			$data = array(
				'created_on' => date('Y-m-d H:i:s'),
				'text' => $text,
				'title' => $title,
				'from_user_id' => $from
			);
			
			list($message_id, $rows) = DB::insert($this->table_name())
				->columns(array_keys($data))
				->values($data)
				->execute($this->_db);
			
			if($message_id)
			{
				$insert = DB::insert('messages_users')
					->columns(array('status', 'user_id', 'message_id', 'parent_id'));

				foreach ($to as $id)
				{
					$insert->values(array(
						'status' => self::STATUS_NEW,
						'user_id' => (int) $id,
						'message_id' => $message_id,
						'parent_id' => (int)$parent_id
					));
					
					self::clear_cache($id);
					Observer::notify('send_message', (int) $id, $text);
				}

				$insert->execute($this->_db);
				
				return $message_id;
			}
		}
		
		return FALSE;
	}

	public function mark_read($user)
	{
		$user = (int) $user;
		
		if(!$this->loaded())
		{
			throw new HTTP_Exception_404('Message not loaded');
		}
		
		if($this->is_read())
		{
			return $this;
		}
		
		DB::update('messages_users')
			->where('user_id', '=', $user)
			->where('message_id', '=', $this->id)
			->set(array(
				'status' => self::STATUS_READ,
				'updated_on' => date('Y-m-d H:i:s')
			))
			->execute($this->_db);
		
		self::clear_cache($user);
		
		return $this;
	}
	
	public function count_new($user)
	{
		return DB::select(array(DB::expr( 'COUNT(*)'), 'total'))
			->from('messages_users')
			->where('status', '=', self::STATUS_NEW)
			->where('user_id', '=', $user)
			->cache_key( 'count_messages::'.$user )
			->cached(3600)
			->execute($this->_db)
			->get('total', 0);
	}
	
	public function delete_by_user($user, $message_id)
	{
		if(empty($message_id))
		{
			return $this;
		}

		$delete = DB::delete('messages_users')
			->where('user_id', '=', (int) $user);
		
		if(is_array($message_id))
		{
			$delete->where('message_id', 'in', $message_id);
		}
		else
		{
			$delete->where('message_id', '=', (int) $message_id);
		}
			
		$delete->execute($this->_db);
		
		self::clear_cache($user);
		
		return $this;
	}
	
	protected static function clear_cache($user_id)
	{
		Kohana::cache('Database::cache(count_messages::'.$user_id.')', NULL, -1);
	}
}