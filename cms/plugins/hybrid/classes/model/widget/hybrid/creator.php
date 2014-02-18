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
		parent::set_values($data);
		
		$this->auto_publish = (bool) Arr::get($data, 'auto_publish');
		$this->data_source_prefix = URL::title(Arr::get($data, 'data_source_prefix'), '_');

		return $this;
	}
	
	public function on_page_load() 
	{
		if($this->ds_id < 1 ) return;

		$this->_errors = array();
		
		$this->_fetch_fields();
	}
	
	protected function _fetch_fields( ) 
	{
		$fields = array('header', 'published');

		$ds_fields = DataSource_Hybrid_Field_Factory::get_related_fields($this->ds_id);
		foreach ($ds_fields as $field)
		{
			$fields[] = $field->name;
		}
		
		$data = array();

		foreach($fields as $field)
		{
			$data[$field] = $this->_get_field_value($field);
		}
		
		$ds = Datasource_Data_Manager::load($this->ds_id);
		$document = $ds->get_empty_document();

		if( ($errors = $document->validate($data)) !== TRUE)
		{
			$this->_errors = $errors;
			$this->_values = $data;
			
			echo debug::vars($errors, $data); return;
			$this->_show_errors();
			return;
		}
		
		if($this->auto_publish === TRUE)
		{
			$data['published'] = TRUE;
		}
		
		$document->read_values($data);
		$doc = $ds->create_document($document);

		$this->_show_success();
	}
	
	public function count_total()
	{
		return 1;
	}
	
	public function fetch_backend_content()
	{
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