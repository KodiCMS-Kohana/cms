<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_Ajax_Photos extends Controller_Ajax_JSON {
	
	public function before() 
	{
		define( 'REST_BACKEND', TRUE );
		parent::before();
	}

	public function action_upload()
	{
		$file = Upload::file($_FILES['file']);
		
		list($status, $filename) = $file;
		
		if($status == TRUE)
		{
			$this->json['status'] = $status;
			$photo = ORM::factory('photo')
				->values(array('category_id' => (int) $this->request->param('id')))
				->create();
			
			$photo->add_image($filename, 'filename');
			
			$this->json['file'] = View::factory('photos/image', array('photo' => $photo, 'category' => $photo->category))->render();
		}
	}
	
	public function action_delete()
	{
		$id = (int) $this->request->post('id');
		$photo = ORM::factory('photo', $id)->delete();
		
		$this->json['status'] = TRUE;
	}
	
	public function action_category_add()
	{
		$category = ORM::factory('photo_category')->values(array(
			'title' => $this->request->post('name'),
			'slug' => $this->request->post('slug'),
			'parent_id' => (int) $this->request->post('parent_id')
		))->create();
		
		$this->json['status'] = TRUE;
		$this->json['category'] = View::factory('photos/category', array('category' => $category))->render();
	}
	
	public function action_category_delete()
	{
		$id = (int) $this->request->post('id');
		
		
		if(ORM::factory('photo_category', $id)->delete())
		{
			$this->json['status'] = TRUE;
		}
	}
	
	public function action_category_image()
	{
		$id = (int) $this->request->post('id');
		$category_id = (int) $this->request->post('category_id');
		
		$photo = ORM::factory('photo', $id);
		$category = ORM::factory('photo_category', $category_id);
		if($photo->loaded() AND $category->loaded())
		{
			$category->values(array(
				'image' => $photo->filename
			))->update();
			
			$this->json['status'] = TRUE;
		}
	}

	public function action_categories_sort()
	{
		$parent_id = (int) $this->request->post('parent_id');
		$data = $this->request->post('pos');
		
		foreach ($data as $pos => $id)
		{
			DB::update('photo_categories')
				->set(array(
					'position' => $pos
				))
				->where('id', '=', $id)
				->where('parent_id', '=', $parent_id)
				->execute();
		}
		
		$this->json['status'] = TRUE;
	}
	
	public function action_categories_move()
	{
		$id = (int) $this->request->post('parent_id');
		$category_id = (int) $this->request->post('category_id');
		
		$category = ORM::factory('photo_category', $id);
		$this->json['status'] = $category->move($category_id);
	}

	public function action_move()
	{
		$id = (int) $this->request->post('id');
		$category_id = (int) $this->request->post('category_id');
		
		$photo = ORM::factory('photo', $id);
		
		if( $photo->move($category_id) )
		{
			if($this->request->post('category_image') == 'true')
			{
				$photo->empty_category_image();
			}

			$this->json['status'] = TRUE;
		}
	}

	public function action_sort()
	{
		$data = $this->request->post('pos');
		$category_id = (int) $this->request->post('category_id');
		
		$old_pos = DB::select('id', 'position')
			->from('photos')
			->where('category_id', '=', $category_id)
			->order_by('position', 'asc')
			->execute()
			->as_array(NULL, 'id');
		
		$diff = array_diff_assoc($old_pos, $data);
		foreach ($data as $pos => $id)
		{
			DB::update('photos')
				->set(array(
					'position' => $pos
				))
				->where('id', '=', $id)
				->where('category_id', '=', $category_id)
				->execute();
		}
		
		$this->json['status'] = TRUE;
	}
}