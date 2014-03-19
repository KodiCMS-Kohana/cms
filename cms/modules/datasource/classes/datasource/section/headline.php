<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Datasource
 */
abstract class Datasource_Section_Headline {
	
	/**
	 *
	 * @var Datasource_Section 
	 */
	protected $_section = NULL;

	/**
	 *
	 * @var array 
	 */
	protected $_sorting = array(
		array('created_on' => 'desc')
	);
	
	/**
	 *
	 * @var Pagination 
	 */
	protected $_pagination = NULL;


	/**
	 *
	 * @var integer 
	 */
	protected $_limit = 20;
	
	/**
	 *
	 * @var integer 
	 */
	protected $_offset = 0;

	/**
	 * 
	 * @param Datasource_Section $section
	 */
	public function __construct(Datasource_Section $section)
	{
		$this->_section = $section;

		$this->_pagination = Pagination::factory();
	}

		/**
	 * @return array Fields
	 */
	public function fields()
	{
		return array(
			'id' => array(
				'name' => 'ID',
				'width' => 50
			),
			'header' => array(
				'name' => 'Header',
				'width' => NULL,
				'type' => 'link'
			)
		);
	}
	
	/**
	 * 
	 * @param type $template
	 * @return type
	 */
	public function render($template = NULL)
	{
		if($template === NULL)
		{
			$template = 'datasource/' . $this->_section->type() . '/headline';
		}

		return View::factory($template, array(
			'fields' => $this->fields(),
			'data' => $this->get(),
			'pagination' => $this->pagination()
		));
	}
	
	public function __toString()
	{
		return (string) $this->render();
	}
	
	public function limit( $limit = NULL )
	{
		if($limit !== NULL)
		{
			$this->_limit = (int) $limit;
			
			if($this->_limit < 1)
			{
				$this->_limit = 1;
			}
		}

		return (int) $this->_limit;
	}
	
	public function offset()
	{
		return (int) $this->_offset;
	}
	
	/**
	 * 
	 * @param array $ids
	 * @param string $search_word
	 * @return Pagination
	 */
	public function pagination( array $ids = NULL )
	{
		$this->_pagination->setup(array(
			'items_per_page' => $this->limit(),
			'total_items' => $this->count_total($ids),
			'current_page' => array(
				'source' => 'query_string',
				'key' => 'page'
			)
		));

		$this->_offset = (int) $this->_pagination->offset;
		
		return $this->_pagination;
	}

	/**
	 * 
	 * @param array $ids
	 * @param string $search_word
	 * @return array
	 */
	abstract public function get( array $ids = NULL );
	
	/**
	 * 
	 * @param array $ids
	 * @param string $search_word
	 * @return integer
	 */
	abstract public function count_total( array $ids = NULL );
	
	/**
	 * 
	 * @param Database_Query $query
	 * @param string $search_word
	 * @return Database_Query
	 */
	public function search_by_keyword( Database_Query $query )
	{
		$keyword = Request::initial()->query('keyword');
		
		if(empty($keyword))
		{
			return $query;
		}

		if($this->_section->is_indexable())
		{
			$query = Search::instance()->get_module_query($query, $keyword, 'ds_' . $this->_section->id());
		}
		else
		{
			$query
				->where_open()
				->where('d.id', 'like', '%'.$keyword.'%')
				->or_where('d.header', 'like', '%'.$keyword.'%')
				->where_close();
		}
		
		return $query;
	}
}