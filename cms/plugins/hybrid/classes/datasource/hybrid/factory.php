<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class DataSource_Hybrid_Factory {

	const PREFIX = 'dshybrid_';
	
	/**
	 * @var array
	 */
	public static $widget_types = array('hybrid_headline', 'hybrid_document');

	/**
	 * 
	 * @param string $key
	 * @param DataSource_Section_Hybrid
	 * @param integer $parent
	 * @return null|\DataSource_Hybrid_Section
	 */
	public function create(DataSource_Section_Hybrid $ds) 
	{

		if(self::create_table($ds->id())) 
		{
			if(self::create_folder($ds->id())) 
			{
				$ds->save();

				return $ds;
			}

			self::remove_table($ds->id());
		}

		$ds->remove();

		return NULL;
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return boolean
	 */
	public function remove($id) 
	{
		self::remove_table($id);
		self::remove_folder($id);
	}
	
	/**
	 * 
	 * @param array $doc_ids
	 * @return null|boolean
	 */
	public function remove_documents($doc_ids) 
	{
		if( !is_array( $doc_ids ) AND strpos(',', $doc_ids ) !== FALSE)
		{
			$doc_ids = explode(',', $doc_ids);
		}
		else if(!is_array( $doc_ids ))
		{
			$doc_ids = array($doc_ids);
		}
		
		if(empty($doc_ids))
		{
			return NULL;
		}

		$query = DB::select('id', 'ds_id')
			->from('dshybrid')
			->where('id', 'in', $doc_ids)
			->order_by('ds_id', 'desc')
			->execute();
		
		$type = array();
		
		foreach ($query as $row)
		{
			$type[$row['ds_id']][] = $row['id'];
		}
		
		foreach ($type as $id => $docs)
		{
			$ds = Datasource_Data_Manager::load($id);
			$ds->remove_own_documents($docs);
		}
		
		unset($ds, $type);
		
		return TRUE;
	}
	
	/**
	 * 
	 * @param array $ids
	 * @return \DataSource_Hybrid_Factory
	 */
	public function publish_documents($ids) 
	{
		return $this->set_published($ids, 1);
	}

	/**
	 * 
	 * @param array $ids
	 * @return \DataSource_Hybrid_Factory
	 */
	public function unpublish_documents($ids) 
	{
		return $this->set_published($ids, 0);
	}
	
	/**
	 * 
	 * @param array $ids
	 * @param boolean $value
	 * @return \DataSource_Hybrid_Factory
	 */
	public function set_published($ids, $value) 
	{
		if( !empty($ids) ) 
		{
			$res = DB::select('dsh.id', 'dsh.ds_id')
				->from(array('dshybrid', 'dsh'))
				->join(array('datasources', 'dss'), 'left')
					->on('dsh.ds_id', '=', 'dss.id')
				->where('dsh.id', 'in', $ids)
				->execute();
			
			$docs = array();
			foreach ($res as $row)
			{
				$docs[$row['ds_id']][] = $row['id'];
			}

			if( !empty($docs) ) 
			{
				$ds_ids = array_keys($docs);

				foreach($ds_ids as $ds_id) 
				{
					$ds = Datasource_Data_Manager::load($ds_id);
					$ids = $docs[$ds_id];
					
					if($value)
					{
						$ds->add_to_index($ids);
					}
					else
					{
						$ds->remove_from_index($ids);
					}
					
					DB::update('dshybrid')
						->set(array(
							'published' => $value,
							'updated_on' => date('Y-m-d H:i:s')
						))
						->where('ds_id', '=', $ds_id)
						->where('id', 'in', $ids)
						->execute();

					unset($ds, $ids);
					
					Datasource_Data_Manager::clear_cache( $ds_id, self::$widget_types);
				}
			}
			
			
		}
		
		return $this;
	}

	/**
	 * 
	 * @param integer $id
	 * @return boolean
	 */
	public static function create_table($id) 
	{
		DB::query(NULL, '
			CREATE TABLE IF NOT EXISTS `:name` (
			 `id` int(11) unsigned NOT NULL default "0",
			 PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8
		')
			->param(':name', DB::expr(self::PREFIX . $id))
			->execute();
		
		return TRUE;
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return boolean
	 */
	public static function remove_table($id) 
	{
		DB::query(NULL, 'DROP TABLE `:name`')
			->param(':name', DB::expr( self::PREFIX . $id))
			->execute();
		
		return TRUE;
	}
	
	/**
	 * 
	 * @param integer $folder
	 * @return boolean
	 */
	public static function create_folder($folder) 
	{
		settype($folder, 'int');
		$dir = PUBLICPATH . 'hybrid' . DIRECTORY_SEPARATOR . $folder;

		if($folder > 0) 
		{
			if(!is_dir($dir))
			{
				mkdir($dir, 0777, TRUE);
			}
			
			chmod($dir, 0777);
			
			return TRUE;
		}

		return FALSE;
	}
	
	/**
	 * 
	 * @param integer $folder
	 * @return boolean
	 */
	public static function remove_folder($folder) 
	{
		settype($folder, 'int');
		$dir = PUBLICPATH . 'hybrid' . DIRECTORY_SEPARATOR . $folder;
	
		if($folder > 0 AND is_dir($dir)) 
		{
			$dir_handle = opendir($dir);
			
			if (!$dir_handle) return FALSE;
			
			while($file = readdir($dir_handle))
			{
				if ($file != "." AND $file != "..")
				{
					unlink($dir . DIRECTORY_SEPARATOR . $file);
				}
			}

			closedir($dir_handle);
			rmdir($dir);
		}

		return !is_dir($dir);
	}
}