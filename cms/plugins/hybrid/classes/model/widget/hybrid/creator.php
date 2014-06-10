<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Hybrid_Creator extends Model_Widget_Hybrid {

	const GET = 1;
	const POST = 2;
	
	public $use_template = FALSE;
	public $use_caching = FALSE;
	
	/**
	 * 
	 * @return array
	 */
	public function src_types()
	{
		return array(
			self::POST => __('POST array'),
			self::GET => __('GET array')
		);
	}
	
	/**
	 * 
	 * @param array $data
	 */
	public function set_values(array $data) 
	{
		if(empty($data['ds_id']) OR ! $this->datasource_exists($data['ds_id']))
		{
			$data['ds_id'] = 0;
		}

		parent::set_values($data);
		
		$this->auto_publish = (bool) Arr::get($data, 'auto_publish');
		$this->data_source_prefix = URL::title(Arr::get($data, 'data_source_prefix'), '_');
		
		if($this->ds_id > 0)
		{
			$email_type_fields = array(
				'key' => array(
					'header',
					'meta_title',
					'meta_keywords',
					'meta_description'
				),
				'value' => array(
					__('Header'),
					__('Meta title'),
					__('Meta keywords'),
					__('Meta description')
				)
			);
			$ds_fields = DataSource_Hybrid_Field_Factory::get_section_fields($this->ds_id);
			
			foreach ($ds_fields as $field)
			{
				$email_type_fields['key'][] = $field->name;
				$email_type_fields['value'][] = $field->header;
			}

			$this->create_email_type($email_type_fields);
		}

		return $this;
	}
	
	public function datasource_exists($ds_id)
	{
		$ds_id = (int) $ds_id;
			
		if($ds_id > 0)
		{
			$ds = Datasource_Section::load( $ds_id );

			if($ds === NULL OR !$ds->loaded())
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
		
		return TRUE;
	}
			

	public function on_page_load() 
	{
		if($this->ds_id < 1 ) return;

		$this->_errors = array();
		
		$this->_fetch_fields();
	}
	
	protected function _fetch_fields( ) 
	{
		$fields = array(
			'header', 
			'published', 
			'meta_title',
			'meta_keywords',
			'meta_description'
		);

		$ds_fields = DataSource_Hybrid_Field_Factory::get_section_fields($this->ds_id);
		foreach ($ds_fields as $field)
		{
			$fields[] = $field->name;
		}
		
		$data = array();

		foreach($fields as $field)
		{
			$data[$field] = $this->_get_field_value($field);
		}
		if(empty($data['meta_title'])) $data['meta_title'] = '';
		if(empty($data['meta_keywords'])) $data['meta_keywords'] = '';
		if(empty($data['meta_description'])) $data['meta_description'] = '';
		
		if($this->auto_publish === TRUE)
		{
			$data['published'] = TRUE;
		}
		
		$ds = Datasource_Data_Manager::load($this->ds_id);
		$document = $ds->get_empty_document();
		
		try
		{
			$document
				->read_values($data)
				->validate();
	
			$document = $ds->create_document($document);
			
			$this->_show_success();
		} 
		catch (Validation_Exception $e)
		{
			$this->_values = $data;
			$this->_errors = $e->errors('validation');
			$this->_show_errors();
			return;
		}
	}
	
	public function count_total() { return 1; }
	
	public function fetch_backend_content()
	{
		if($this->ds_id > 0 AND ! $this->datasource_exists($this->ds_id))
		{
			$this->ds_id = 0;
			Widget_Manager::update($this);
		}
		
		try
		{
			$content = View::factory( 'widgets/backend/' . $this->backend_template(), array(
					'widget' => $this
				))->set($this->backend_data());
		}
		catch( Kohana_Exception $e)
		{
			$content = NULL;
		}
		
		return $content;
	}
	
	public function fetch_data()
	{
		return array();
	}
	
	protected function _show_success()
	{
		if( ! empty($this->redirect_url)) 
		{
			$url = URL::site($this->redirect_url);
		}
		else
		{
			$url = Request::current()->referrer();
		}
		
		$query = URL::query(array('status' => 'ok'), FALSE);
		
		HTTP::redirect( preg_replace('/\?.*/', '', $url) . $query, 302);
	}
	
	protected function _show_errors()
	{
		if(Request::current()->is_ajax())
		{
			$json = array('status' => FALSE);
			
			$json['errors'] = $this->_errors;
			$json['values'] = $this->_values;
			
			Request::current()->headers( 'Content-type', 'application/json' );		
			$this->_ctx->response()->body(json_encode($json));
			
			return;
		}
		
		Flash::set('form_errors', $this->_errors);
		Flash::set('form_values', $this->_values);

		$query = URL::query(array('status' => 'error'), FALSE);

		$referrer = Request::current()->referrer();
		HTTP::redirect( preg_replace('/\?.*/', '', $referrer) . $query, 302);
	}

	protected function _get_field_value( $field ) 
	{
		$value = NULL;

		$source = array();

		$src = $this->data_source % 10;
		switch($src) 
		{
			case self::GET:
				$source = Request::current()->query();	
				break;
			case self::POST:
				$source = Request::current()->post();	
				break;
		}
		
		$source += $_FILES;
		
		if( ! empty($this->data_source_prefix) )
		{
			$value = Arr::path($source, $this->data_source_prefix . '.' . $field);
		}
		else
		{
			$value = Arr::get($source, $field);
		}

		return $value;
	}
}