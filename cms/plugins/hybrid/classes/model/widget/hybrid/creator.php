<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Hybrid_Creator extends Model_Widget_Decorator_Handler {

	const GET = 1;
	const POST = 2;
	
	/**
	 *
	 * @var array 
	 */
	public $doc_fields = array();

	/**
	 *
	 * @var integer|NULL 
	 */
	protected $_document_id = NULL;
	
	/**
	 *
	 * @var array 
	 */
	protected $_errors = array();

	/**
	 *
	 * @var array
	 */
	protected $_values = array();

	/**
	 *
	 * @var boolean 
	 */
	public $status = FALSE;

	/**
	 *
	 * @var array 
	 */
	protected $_data = array(
		'auto_publish' => FALSE,
		'disable_update' => TRUE
	);
	
	/**
	 *
	 * @var array 
	 */
	public $response = array(
		'status' => FALSE
	);

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

	public function on_page_load() 
	{
		if ($this->ds_id < 1)
		{
			return;
		}

		$this->_values = $this->_fetch_fields();
		$this->_document_id = $this->_handle_document($this->_values);

		if (!empty($this->_errors))
		{
			$this->_show_errors();
		}
		else
		{
			$this->_show_success();
		}
	}
	
	/**
	 * 
	 * @param array $data
	 */
	public function set_values(array $data) 
	{
		if (empty($data['ds_id']) OR ! Datasource_Data_Manager::exists($data['ds_id']))
		{
			$data['ds_id'] = 0;
		}

		$this->doc_fields = array();

		parent::set_values($data);

		$this->auto_publish = (bool) Arr::get($data, 'auto_publish');
		$this->disable_update = (bool) Arr::get($data, 'disable_update');

		$this->data_source_prefix = URL::title(Arr::get($data, 'data_source_prefix'), '_');

		if ($this->ds_id > 0)
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
	
	/**
	 * 
	 * @param array $fields
	 * @return \Model_Widget_Hybrid_Profile
	 */
	public function set_field($fields = array())
	{
		if (!is_array($fields))
		{
			return;
		}

		foreach ($fields as $f)
		{
			if (isset($f['id']))
			{
				$this->doc_fields[] = (int) $f['id'];
			}
		}

		return $this;
	}
	
	/**
	 * 
	 * @param array $data
	 * @return null|integer
	 */
	protected function _handle_document($data, Validation $external_validation = NULL)
	{
		$ds = Datasource_Data_Manager::load($this->ds_id);

		$create = TRUE;
		
		if (empty($data['id']) OR $this->disable_update === TRUE)
		{
			$document = $ds->get_empty_document();
		}
		else
		{
			$id = (int) $data['id'];
			$document = $ds->get_document($id);
			$create = FALSE;

			if (!$document->loaded())
			{
				$this->_errors = __('Document ID :id not found', array(':id' => $id));
				return NULL;
			}
		}

		try
		{
			if ($this->auto_publish === TRUE AND ! isset($data['published']))
			{
				$data['published'] = TRUE;
			}

			$document
					->read_values($data, $this->doc_fields)
					->read_files($data)
					->validate($external_validation, $this->doc_fields);

			if ($create === TRUE)
			{
				$ds->create_document($document);
			}
			else
			{
				$ds->update_document($document);
			}

			$this->handle_email_type($data);
			$this->status = TRUE;

			return $document->id;
		} 
		catch (Validation_Exception $e)
		{
			$this->_errors = $e->errors('validation');
			return NULL;
		}
		catch (DataSource_Exception_Document $e)
		{
			$this->_errors = $e->getMessage();
			return NULL;
		}
	}

	/**
	 * 
	 * @return array
	 */
	protected function _fetch_fields() 
	{
		$fields = array(
			'csrf', // Security token
			'id',
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
		
		return $data;
	}
	
	public function fetch_backend_content()
	{
		if($this->ds_id > 0 AND ! Datasource_Data_Manager::exists($this->ds_id))
		{
			$this->ds_id = 0;
			Widget_Manager::update($this);
		}
		
		return parent::fetch_backend_content();
	}
	
	protected function _send_http_reponse()
	{
		if( ! empty($this->redirect_url)) 
		{
			$url = URL::site($this->redirect_url);
		}
		else
		{
			$url = Request::current()->referrer();
		}
		
		$query = URL::query($this->response, FALSE);
		HTTP::redirect( preg_replace('/\?.*/', '', $url) . $query, 302);
	}

	protected function _show_success()
	{
		$this->response['status'] = TRUE;

		if (Request::current()->is_ajax())
		{
			if (!empty($this->redirect_url))
			{
				$this->response['redirect'] = URL::site($this->redirect_url);
			}

			Request::current()->headers('Content-type', 'application/json');
			$this->_ctx->response()->body(json_encode($this->response));

			return;
		}

		$this->_send_http_reponse();
	}

	protected function _show_errors()
	{
		$this->response['status'] = FALSE;

		if (Request::current()->is_ajax())
		{
			$this->response['errors'] = $this->_errors;
			$this->response['values'] = $this->_values;

			Request::current()->headers('Content-type', 'application/json');
			$this->_ctx->response()->body(json_encode($this->response));
			return;
		}

		Flash::set('form_errors', $this->_errors);
		Flash::set('form_values', $this->_values);

		$this->_send_http_reponse();
	}

	protected function _get_field_value($field)
	{
		$source = array();

		$src = $this->data_source % 10;
		switch ($src)
		{
			case self::GET:
				$source = Request::current()->query();
				break;
			case self::POST:
				$source = Request::current()->post();
				break;
		}

		$source += $_FILES;

		if (!empty($this->data_source_prefix))
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