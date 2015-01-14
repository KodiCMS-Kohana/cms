<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Reflink
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
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
	
	protected $_serialize_columns = array('data');

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
		if (!$user->loaded())
		{
			throw new Reflink_Exception(' User not loaded ');
		}

		$type = URL::title($type, '_');
		
		$reflink = $this
			->reset(FALSE)
			->where('user_id', '=', $user->id)
			->where('type', '=', $type)
			->where('created', '>', DB::expr('CURDATE() - INTERVAL 1 HOUR'))
			->find();

		if (!$reflink->loaded())
		{
			$values = array(
				'user_id'	=> (int) $user->id,
				'code'		=> uniqid(TRUE) . sha1(microtime()),
				'type'		=> $type,
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
		if (!$this->loaded())
		{
			throw new Reflink_Exception('Model not loaded or not found.');
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