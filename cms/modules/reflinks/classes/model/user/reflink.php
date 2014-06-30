<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Reflink
 * @category	Model
 * @author		ButscHSter
 */
class Model_User_Reflink extends ORM {

	protected $_primary_key = 'code';
	
	protected $_created_column = array(
		'column' => 'created',
		'format' => 'Y-m-d H:i:s'
	);

	protected $_belongs_to = array(
		'user' => array(),
	);

	public function get($column) 
	{
		if($column == 'data')
		{
			return unserialize($this->_object[$column]);
		}

		return parent::get($column);
	}

	/**
	 * Generate new reflink code
	 *
	 * @param   Model_User  $user
	 * @param   integer		$type	reflink type
	 * @param   string		$data	string stored to reflink in database
	 * @return  string
	 */
	public function generate(Model_User $user, $type, $data = NULL) 
	{
		if ( ! $user->loaded() ) 
		{
			throw new Reflink_Exception(' User not loaded ');
		}
		
		$data = serialize($data);

		$reflink = $this
			->reset(FALSE)
			->where('user_id', '=', $user->id)
			->where('type', '=', (int) $type)
			->where('created', '>', DB::expr('CURDATE() - INTERVAL 1 HOUR'))
			->find();

		if ( ! $reflink->loaded() ) 
		{
			$values = array(
				'user_id'	=> (int) $user->id,
				'code'		=> uniqid(TRUE) . sha1(microtime()),
				'type'		=> (int) $type,
				'data'		=> $data
			);

			$reflink = ORM::factory('user_reflink')
				->values($values, array_keys($values))
				->create();
		} 
		else 
		{
			$reflink
				->set('data', $data)
				->update();
		}

		return $reflink->code;
	}

	/**
	 * Delete reflinks from database
	 * Model must be loaded
	 *
	 * @return  integer
	 */
	public function delete() 
	{
		if ( ! $this->loaded() ) 
		{
			throw new Reflink_Exception( 'Model not loaded or not found.' );
		}

		return DB::delete($this->table_name())
			->where('user_id', '=', $this->user_id)
			->where('type', '=', $this->type)
			->execute($this->_db);
	}

	/**
	 * Delete old reflinks from database
	 *
	 * @return  integer
	 */
	public function clear_old() 
	{
		return DB::delete($this->table_name())
			->where('created', '<', DB::expr('CURDATE() - INTERVAL 1 DAY'))
			->execute($this->_db);
	}
}