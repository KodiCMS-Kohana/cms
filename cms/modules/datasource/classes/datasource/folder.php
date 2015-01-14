<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Datasource
 * @category	Section
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Datasource_Folder {
	
	/**
	 * Список всех папок
	 * 
	 * @return array
	 */
	public static function get_all()
	{
		$query = DB::select()
			->from('datasource_folders')
			->order_by('position')
			->as_object()
			->execute();

		$folders = array();

		foreach($query as $row)
		{
			$folders[$row->id] = array(
				'name' => $row->name,
				'sections' => array()
			);
		}

		return $folders;
	}

	/**
	 * 
	 * @param integer $id
	 * @return boolean
	 */
	public static function exists($id)
	{
		return (bool) DB::select('id')
			->from('datasource_folders')
			->where('id', '=', (int) $id)
			->limit(1)
			->execute()
			->get('id');
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return array
	 */
	public static function get($id)
	{
		return DB::select()
			->from('datasource_folders')
			->where('id', '=', (int) $id)
			->limit(1)
			->execute();
	}
	
	/**
	 * 
	 * @param string $name
	 * @param string $description
	 * @return integer
	 */
	public static function add($name)
	{
		list($id, $total) = DB::insert('datasource_folders')
			->columns(array('name'))
			->values(array($name))
			->execute();
		
		return $id;
	}
	
	/**
	 * 
	 * @param string $name
	 * @param string $description
	 * @return integer
	 */
	public static function update($name)
	{
		return (bool) DB::update('datasource_folders')
			->set(array(
				'name' => $name
			))
			->execute();
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return boolean
	 */
	public static function delete($id)
	{
		if ((bool) DB::delete('datasource_folders')
			->where('id', '=', (int) $id)
			->execute())
		{
			DB::update('datasources')
				->set(array(
					'folder_id' => 0
				))
				->where('folder_id', '=', (int) $id)
				->execute();
			
			return TRUE;
		}
		
		return FALSE;
	}
}
	