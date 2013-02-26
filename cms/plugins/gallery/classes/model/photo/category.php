<?php defined('SYSPATH') or die('No direct script access.');

class Model_Photo_Category extends ORM {
	
	protected $_has_many = array(
		'photos' => array('model' => 'photo', 'foreign_key' => 'category_id' ),
	);
	
	protected $_sorting = array(
		'position' => 'asc'
	);

	public function rules()
	{
		return array(
			'title' => array(
				array('not_empty'),
				array('min_length', array(':value', 2)),
				array('max_length', array(':value', 200)),
			),
		);
	}
	
	public function filters()
	{
		return array(
			'title' => array(
				array('Kses::filter')
			),
			'slug' => array(
				array('Url::title')
			),
		);
	}
	
	/**
	 * 
	 * @return array 
	 */
	public function get_parents()
	{
		$parent = $this;
		$parents = array();
		
		while ( $parent->loaded() )
		{
			$parents[] = $parent;
			$parent = $parent->parent();
		}
		
		return array_reverse($parents);
	}
	
	/**
	 * 
	 * @return \self
	 */
	public function parent()
	{
		return new self($this->parent_id);
	}
	
	/**
	 * 
	 * @param integer $category_id
	 * @return boolean
	 */
	public function move($category_id)
	{
		$category = ORM::factory('photo_category', $category_id);
		
		if( ($category_id > 0 AND ! $category->loaded()) OR ! $this->loaded())
		{
			return FALSE;
		}
		
		$old_path = $this->path;
		$new_path = $category->path . DIRECTORY_SEPARATOR . $this->slug;
		
		$status = FALSE;
		
		foreach ( ORM::factory('photo')->images() as $path => $data)
		{
			$old_dir = PUBLICPATH . $path . DIRECTORY_SEPARATOR . $old_path;
			$new_dir = PUBLICPATH . $path . DIRECTORY_SEPARATOR . $new_path;

			if(is_dir($old_dir) AND !is_dir($new_dir))
			{
				$status = rename($old_dir, $new_dir);
			}
		}
		
		if($status === TRUE)
		{
			$this->values(array(
				'parent_id' => $category_id,
				'path' => $new_path
			))->update();

			$this->rebuild_paths($this->id);
		}

		return $status;
	}
	
	/**
	 * 
	 * @return boolean|string
	 */
	public function get_path()
	{
		if( ! $this->loaded())
		{
			return FALSE;
		}
		
		$path = $this->slug;
		
		if($this->parent_id > 0)
		{
			$path = $this->parent()->path . '/' . $path;
		}
		
		return $path;
	}

	/**
	 * 
	 * @param integer $parent_id
	 * @return \Model_Photo_Category
	 */
	public function rebuild_paths($parent_id = 0)
	{
		$categories = $this
			->clear()
			->where('parent_id', '=', (int) $parent_id)
			->find_all();
		
		foreach ($categories as $category)
		{
			$category
				->set('path', $category->get_path())
				->update();

			$category->rebuild_paths($category->id);
		}
		
		return $this;
	}

	/**
	 * 
	 * @return integer
	 */
	public function get_next_position()
	{
		$last_position = DB::select(array(DB::expr('MAX(position)'), 'pos'))
			->from($this->table_name())
			->execute($this->_db)
			->get('pos', 0);
		
		return ((int) $last_position) + 1;
	}
	
	/**
	 * 
	 * @param \Validation $validation
	 * @return \self
	 */
	public function create(\Validation $validation = NULL) 
	{
		if ($this->position == 0)
		{
			$this->position = $this->get_next_position();
		}

		return parent::create($validation);
	}

	public function delete_folder()
	{
		if( ! $this->loaded())
		{
			return FALSE;
		}

		foreach ( ORM::factory('photo')->images() as $path => $data)
		{
			$dir = PUBLICPATH . $path . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR;
			$this->delete_by_path($dir);
		}
		
		return $this;
	}
	
	public function delete_by_path($path)
	{
		if(is_dir($path))
		{
			$files = glob($path . '*', GLOB_MARK);
			foreach ($files as $file)
			{
				if (is_dir($file)) 
				{
					$this->delete_by_path($file);
				} 
				else 
				{
					unlink($file);
				}
			}

			rmdir($path);
		}
	}	

	public function delete()
	{
		$photos = ORM::factory('photo')
			->where('category_id', '=', $this->id)
			->find_all();
		
		$id = $this->id;
		$parent_id = $this->parent_id;
		$path = $this->path;
		
		$categories = ORM::factory('photo_category')
			->where('parent_id', '=', $this->id)
			->find_all();
		
		$moved = TRUE;
		foreach ($categories as $category)
		{
			$moved = $category->move($this->parent_id);
		}
		
		if($moved === FALSE) return FALSE;
		
		$this->delete_folder();

		if ( parent::delete() ) 
		{
			ORM::factory('photo')->delete_by_category($id);
		}
		
		return $this;
	}
}