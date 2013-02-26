<?php defined('SYSPATH') or die('No direct script access.');

class Model_Photo extends ORM {
	
	protected $_created_column = array(
		'column' => 'created_on',
		'format' => 'Y-m-d H:i:s'
	);
	
	protected $_belongs_to = array(
		'category' => array('model' => 'photo_category')
	);

	protected $_sorting = array(
		'position' => 'asc'
	);
	
	protected $_loaded_with = array(
		'category'
	);

	public function images()
	{	
		return array(
			'photos' . DIRECTORY_SEPARATOR . 'full' => array(
				'subfolder' => $this->category->path,
				'quality' => 85
			),
			'photos' . DIRECTORY_SEPARATOR . '120_120' => array(
				'subfolder' => $this->category->path,
				'width' => 120,
				'height' => 120,
				'quality' => 85,
				'master' => Image::AUTO,
			),
		);
	}
	
	public function get_next_position()
	{
		$last_position = DB::select(array(DB::expr('MAX(position)'), 'pos'))
			->from($this->table_name())
			->where('category_id', '=', $this->category_id)
			->execute($this->_db)
			->get('pos', 0);
		
		return ((int) $last_position) + 1;
	}

	public function create(\Validation $validation = NULL) 
	{
		if ($this->position == 0)
		{
			$this->position = $this->get_next_position();
		}

		return parent::create($validation);
	}
	
	public function empty_category_image()
	{
		DB::update('photo_categories')
			->where('image', '=', $this->filename)
			->set(array('image' => ''))
			->execute($this->_db);
		
		return $this;
	}
	
	
	public function unlink_files()
	{
		foreach ($this->images() as $path => $data)
		{
			$file = PUBLICPATH . $path . DIRECTORY_SEPARATOR . $this->category->path . $this->filename;
			if(file_exists($file))
			{
				unlink($file);
			}
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @param integer $category_id
	 * @return boolean
	 */
	public function move($category_id)
	{
		$category = ORM::factory('photo_category', $category_id);
		$status = FALSE;
		
		if( ($category_id > 0 AND ! $category->loaded()) OR ! $this->loaded() )
		{
			return FALSE;
		}
		
		foreach ($this->images() as $path => $data)
		{
			$old_dir = PUBLICPATH . $path . DIRECTORY_SEPARATOR . $this->category->path . DIRECTORY_SEPARATOR;
			$new_dir = PUBLICPATH . $path . DIRECTORY_SEPARATOR . $category->path . DIRECTORY_SEPARATOR;

			if(file_exists($old_dir . $this->filename))
			{
				if( ! is_dir($new_dir) )
				{
					mkdir( $new_dir, 0777, TRUE );
					chmod( $new_dir, 0777 );
				}

				$status = rename($old_dir . $this->filename, $new_dir . $this->filename);
			}
		}
		
		if($status === TRUE)
		{
			$this->set('category_id', $category_id)->update();
		}
		
		return $status;
	}

	public function delete()
	{
		if ( ! $this->loaded() )
		{
			throw new Kohana_Exception('photo not loaded');
		}
		
		$this->empty_category_image();
		$this->unlink_files();
		
		return parent::delete();
	}
	
	public function delete_by_category($id)
	{
		$photos = $this->reset(FALSE)
			->where('category_id', '=', (int) $id)
			->find_all();
		
		foreach ($photos as $photo)
		{
			$photo->delete();
		}
		
		return $this;
	}

	public function src($folder = '120_120')
	{
		return PUBLIC_URL . 'photos/' . $folder . '/' . $this->category->path . '/' . $this->filename;
	}

	public function filter_by_category($id)
	{
		 return $this
			->where('category_id', '=', (int) $id);
	}
}