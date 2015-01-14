<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Hybrid_Document extends Model_Widget_Decorator {
			
	/**
	 *
	 * @var array
	 */
	protected static $_cached_documents = array();
	
	/**
	 *
	 * @var array 
	 */
	public $doc_fields = array();
	
	/**
	 *
	 * @var array 
	 */
	public $doc_fetched_widgets = array();
	
	/**
	 *
	 * @var string 
	 */
	public $doc_id_field = 'id';

	/**
	 *
	 * @var bool 
	 */
	public $crumbs = FALSE;
	
	/**
	 *
	 * @var integer 
	 */
	protected $_id = NULL;
	
	/**
	 *
	 * @var string 
	 */
	public $doc_id_ctx = 'item';

	/**
	 * 
	 * @param array $data
	 */
	public function set_values(array $data) 
	{
		$this->doc_fields = $this->doc_fetched_widgets = array();
		
		parent::set_values($data);
		
		$this->docs_uri = Arr::get($data, 'docs_uri', $this->docs_uri);
		$this->doc_id_field = Arr::get($data, 'doc_id_field', $this->doc_id_field);

		$doc_id_ctx = Arr::get($data, 'doc_id_ctx');
		$this->doc_id_ctx = empty($doc_id_ctx) 
			? $this->doc_id_ctx 
			: $doc_id_ctx;
		
		$this->throw_404 = (bool) Arr::get($data, 'throw_404');
		$this->crumbs = (bool) Arr::get($data, 'crumbs');
		$this->seo_information = (bool) Arr::get($data, 'seo_information');
		
		return $this;
	}
	
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

				if (isset($f['fetcher']))
				{
					$this->doc_fetched_widgets[(int) $f['id']] = (int) $f['fetcher'];
				}
			}
		}

		return $this;
	}

	public function get_doc_ids()
	{
		$data = array('ID');

		$fields = DataSource_Hybrid_Field_Factory::get_section_fields($this->ds_id);
		foreach ($fields as $field)
		{
			if ($field->use_as_document_id())
			{
				$data[$field->id] = $field->header;
			}
		}

		return $data;
	}

	public function on_page_load()
	{
		parent::on_page_load();

		$doc = $this->get_document();

		if (empty($doc) AND $this->throw_404)
		{
			$this->_ctx->throw_404();
		}

		if ($this->seo_information === TRUE)
		{
			$page = $this->_ctx->get_page();

			$page->meta_params('document_header', $doc['header'], 'title');
			$page->meta_params('document_meta_title', $doc['meta_title'], 'meta_title');
			$page->meta_params('document_meta_keywords', $doc['meta_keywords'], 'meta_keywords');
			$page->meta_params('document_meta_description', $doc['meta_description'], 'meta_description');
		}
	}

	/**
	 * 
	 * @return array [$doc]
	 */
	public function fetch_data()
	{
		$result = array();

		if (!$this->ds_id)
		{
			return $result;
		}

		$result = $this->get_document();

		return array(
			'doc' => $result
		);
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return array
	 */
	public function get_document($id = NULL, $recurse = 3)
	{
		$result = array();
		
		if ($id === NULL)
		{
			$id = $this->get_doc_id();
		}

		if (empty($id))
		{
			return $result;
		}

		if (isset(Model_Widget_Hybrid_Document::$_cached_documents[$id]))
		{
			return Model_Widget_Hybrid_Document::$_cached_documents[$id];
		}

		$agent = DataSource_Hybrid_Agent::instance($this->ds_id);
		$result = $agent->get_document($id, $this->doc_fields, $this->doc_id_field);

		if (empty($result))
		{
			return $result;
		}

		$hybrid_fields = $agent->get_fields();
		foreach ($result as $key => $value)
		{
			if (!isset($hybrid_fields[$key]))
			{
				continue;
			}

			$field = & $hybrid_fields[$key];

			$field_class_method = 'fetch_widget_field';

			$result['_' . $field->key] = $result[$key];

			if (method_exists($field, $field_class_method))
			{
				$result[$field->key] = $field->$field_class_method($this, $field, $result, $key, $recurse - 1);
			}
			else
			{
				$result[$field->key] = $result[$key];
			}

			unset($result[$key]);
		}

		Model_Widget_Hybrid_Document::$_cached_documents[$id] = $result;

		return $result;
	}
	
	/**
	 * 
	 * @param integer $id
	 */
	public function set_doc_id($id)
	{
		$this->_id = (int) $id;
	}

	public function get_doc_id()
	{
		if (Valid::numeric($this->_id))
		{
			return $this->_id;
		}

		return $this->_ctx->get($this->doc_id_ctx);
	}
	
	public function get_cache_id()
	{
		if (IS_BACKEND)
		{
			return;
		}

		return 'Widget::' 
			. $this->type . '::' 
			. $this->id . '::' 
			. $this->get_doc_id();
	}
	
	public function count_total()
	{
		return 1;
	}
	
	public function __sleep()
	{
		$vars = get_object_vars($this);
		unset($vars['_id'], $vars['document'], $vars['_ctx']);

		return array_keys($vars);
	}
}