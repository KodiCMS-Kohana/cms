<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Source_Images extends DataSource_Hybrid_Field_Source {
	
	protected $_props = array(
		'max_size' => 1048576
	);
	
	public function module_id()
	{
		return 'field_' . $this->id;
	}

	public function load( $ids )
	{
		if(!is_array($ids))
		{
			$ids = explode(',', $ids);
		}
		
		return ORM::factory('media')
			->where('id', 'in', $ids)
			->find_all()
			->as_array('id', 'filename');
	}

	public function onCreateDocument( DataSource_Hybrid_Document $doc )
	{
		return $this->onUpdateDocument( $doc, $doc );
	}
	
	public function onUpdateDocument( DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new )
	{
		$files = Arr::get($_FILES, $this->name);
		
		$remove_files = $new->get($this->name . '_remove');
		if( ! empty($remove_files))
		{
			ORM::factory('media')->delete_by_ids($remove_files);
		}

		if(empty($files))
		{
			return FALSE;
		}
		
		$old_files = $old->get($this->name);
		$old_files = empty($old_files) ? array() : explode(',', $old_files);

		$files = $this->_normalize_files($files);
		
		foreach ($files as $file)
		{
			if( ! Upload::not_empty($file) ) continue;
			try
			{
				$uploaded_file = ORM::factory('media')
					->set('module', $this->module_id())
					->upload($file, array('jpg', 'jpeg', 'gif', 'png'), $this->max_size);

				if($uploaded_file->loaded())
				{
					$old_files[] = $uploaded_file->id;
				}
			} 
			catch (Exception $ex) 
			{
				continue;
			}
		}
		
		$new->set($this->name, implode(',', $old_files));

		return TRUE;
	}
	
	public function onRemoveDocument(DataSource_Hybrid_Document $doc)
	{
		$ids = $doc->get($this->name);

		if ( ! empty($ids) )
		{
			ORM::factory('media')->delete_by_ids(explode(',', $ids));

			$doc->set($this->name, '');
		}
	}
	
	public function remove()
	{
		$images = ORM::factory('media')
			->where('module', '=', $this->module_id())
			->find_all();
		
		foreach ($images as $image)
		{
			$image->delete();
		}
		
		return parent::remove();
	}
	
	public function get_type()
	{
		return 'TEXT NOT NULL';
	}
	
	protected function _normalize_files( $files ) 
	{
		$file_ary = array();
		$file_count = count($files['name']);
		$file_keys = array_keys($files);

		for ($i=0; $i<$file_count; $i++) 
		{
			foreach ($file_keys as $key) 
			{
				$file_ary[$i][$key] = $files[$key][$i];
			}
		}

		return $file_ary;
	}

	public static function fetch_widget_field( $widget, $field, $row, $fid, $recurse )
	{
		return (!empty($row[$fid]) ? $field->load($row[$fid]) : array());
	}
}