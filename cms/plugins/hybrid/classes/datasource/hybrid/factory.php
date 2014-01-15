<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class DataSource_Hybrid_Factory {
	
	const TABLE = 'hybriddatasources';
	const SEPARATOR = '.';
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
	public function create($key, DataSource_Section_Hybrid $ds, $parent = 0) 
	{
		$parent = (int) $parent;

		$key = self::get_full_key($key, $parent);

		if(self::create_table($ds->id())) 
		{
			if(self::create_folder($ds->id())) 
			{
				self::update_struct($ds);
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
		$ids = $this->get_children($id);

		if(!sizeof($ids))
		{
			return FALSE;
		}

		foreach($ids as $_id) 
		{
			if($_id != $id)
			{
				$ds = Datasource_Data_Manager::load($_id);
				if(!$ds) continue;
				$ds->remove();
			}
			

			self::remove_table($_id);
			self::remove_folder($_id);
		}
		
		return (bool) DB::delete(self::TABLE)
			->where('ds_id', 'in', $ids)
			->execute();
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
	 * @param array $ids
	 * @param integer $fromId
	 * @param integer $toId
	 */
	public function cast_documents($ids, $fromId, $toId) 
	{
		if(sizeof($ids) > 0) 
		{

			$from = Datasource_Data_Manager::load($fromId);
			$to = Datasource_Data_Manager::load($toId);

			$res = DB::select('id')
				->from('dshybrid')
				->where('ds_id', '=', $from->id())
				->where('id', 'in', $ids)
				->execute();
			
			if(count($res) > 0) 
			{
				$add = $remove = array();
				$fromRec = $from->get_record(); 
				$toRec = $to->get_record();
				
				$path1 = explode(',', $from->path); 
				$path2 = explode(',', $to->path);

				$removeDs = array_diff($path1, $path2);
				$addDs = array_diff($path2, $path1);
				$commonDs = (int) max(array_intersect($path1, $path2));

				foreach($fromRec->fields as $key => $field)
				{
					if(!(isset($toRec->fields[$key]) AND $toRec->fields[$key]->ds_id == $field->ds_id))
					{
						$remove[] = $fromRec->fields[$key];
					}
				}

				foreach($toRec->fields as $key => $field)
				{
					if(!(isset($fromRec->fields[$key]) && $fromRec->fields[$key]->ds_id == $field->ds_id))
					{
						$add[] = $toRec->fields[$key];
					}
				}

				$lr = sizeof($remove); 
				$la = sizeof($add);
				$ids = array();

				foreach ($res as $row)
				{
					$doc = $from->get_document($row['id']);
					for($r = 0; $r < $lr; $r++)
					{
						$remove[$r]->onRemoveDocument($doc);
					}
					
					$ids[] = $doc->id;
				}

				if(sizeof($ids)) 
				{
					$failed = array();

					if(sizeof($removeDs)) 
					{
						foreach($removeDs as $dsId)
						{
							DB::delete('dshybrid_'. (int) $dsId)
								->where('id', 'in', $ids)
								->execute();
						}
					}

					foreach($ids as $k => $id) 
					{
						$success = TRUE;
						foreach($addDs as $dsId) 
						{
							$query = DB::insert('dshybrid_'. (int) $dsId)
								->columns(array('id'))
								->values(array($id))
								->execute();

							$success = $success && ($query[1] > 0);
						}
						
						if(!$success) 
						{
							foreach($addDs as $dsId)
							{
								DB::delete('dshybrid_'. (int) $dsId)
									->where('id', '=', $id)
									->execute();
							}

							$failed[] = $id;
							unset($ids[$k]);
						}
					}

					if(sizeof($failed)) 
					{
						if($commonDs > 0)
						{
							DB::update('dshybrid')
								->set(array(
									'ds_id' => $commonDs
								))
								->where('id', 'in', $failed)
								->execute();
						}
						else
						{
							DB::delete('dshybrid')
									->where('id', 'in', $failed)
									->execute();
						}
					}

					if(sizeof($ids))
					{
						foreach($ids as $id) 
						{
							$doc = $to->get_document($id);
							for($a = 0; $a < $la; $a++)
							{
								$add[$a]->onCreateDocument($doc);
							}

							$query = $toRec->get_sql($doc);
							foreach($query as $q)
							{
								$db->query($q);
							}
						}

						DB::update('dshybrid')
							->set(array('ds_id' => $to->id()))
							->where('id', 'in', $ids)
							->execute();
			
						$from->update_size();
						$to->update_size();
					}
				}
			}
		}
	}
	
	/**
	 * 
	 * @param integer $ds_id
	 * @return array
	 */
	public static function get_children($ds_id) 
	{
		return DB::select(array('t2.ds_id', 'id'))
			->from(array(self::TABLE, 't1'), array(self::TABLE, 't2'))
			->where('t1.ds_id', '=', (int) $ds_id)
			->where(DB::expr('INSTR(:f1, :f2)', array(
				':f1' => DB::expr(Database::instance()->quote_column('t2.ds_key')), 
				':f2' => DB::expr(Database::instance()->quote_column('t1.ds_key'))
			)), '=', 1)
			->order_by('t2.ds_key', 'desc')
			->execute()
			->as_array(NULL, 'id');
	}
	
	/**
	 * 
	 * @param string $key
	 * @param integer $parent
	 * @return string
	 */
	public static function get_full_key($key, $parent)
	{
		$key = self::validate_key($key);
		if(!$parent)
		{
			return $key;
		}

		$fullkey = DB::select('ds_key', 'path')
			->from(self::TABLE)
			->where('ds_id', '=', $parent)
			->execute()
			->get('ds_key');
		
		if($fullkey)
		{
			$fullkey .= self::SEPARATOR . $key;
		}
		
		return $fullkey;
	}
	
	/**
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public static function exists($key) 
	{
		return ! (bool) DB::select('ds_id')
			->from(self::TABLE)
			->where('ds_key', '=', $key)
			->execute()
			->get('ds_id');
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
	 * @param Datasource_Section $ds
	 * @return array
	 */
	public static function update_struct($ds) 
	{
		if($ds->parent) 
		{
			$path = DB::select('path')
				->from(self::TABLE)
				->where('ds_id', '=', $ds->parent)
				->execute()
				->get('path');

			$ds->path = $path . ',' . $ds->id();
		}
		else
		{
			$ds->path = '0,' . $ds->id();
		}
		
		$data = array(
			'ds_id' => $ds->id(), 
			'parent' => (int) $ds->parent, 
			'ds_key' => $ds->key, 
			'path' => $ds->path
		);
		
		return DB::insert(self::TABLE)
			->columns(array_keys($data))
			->values(array_values($data))
			->execute();
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
	
	/**
	 * 
	 * @param string $key
	 * @return string
	 */
	public static function validate_key($key) 
	{
		$key = preg_replace('/[^A-Za-z0-9]+/', '', $key);
		$key = strtolower($key);
		if(strlen($key) > 16)
		{
			$key = substr($key, 0, 16);
		}

		return $key;
	}
}