<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Datasource
 * @category	Headline
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
abstract class Datasource_Section_Headline {
	
	/**
	 * Объект раздела
	 * 
	 * @var Datasource_Section 
	 */
	protected $_section = NULL;

	/**
	 * Правила сортировки списка документов
	 * @var array 
	 */
	protected $_sorting = array(
		array('created_on' => 'desc')
	);
	
	/**
	 * Объект постраничной навигации
	 * @var Pagination 
	 */
	protected $_pagination = NULL;


	/**
	 * Кол-во документов выводимых на 1 странице
	 * По умолчанию 20
	 * 
	 * Используется объектом постраничной навигации
	 * 
	 * @var integer 
	 */
	protected $_limit = 20;
	
	/**
	 * Кол-во пропускаемых документов
	 * 
	 *  Используется объектом постраничной навигации
	 * 
	 * @var integer 
	 */
	protected $_offset = 0;
	
	/**
	 * Текущая страница
	 * @var integer 
	 */
	protected $_page = NULL;

	/**
	 * 
	 * @param Datasource_Section $section
	 */
	public function __construct()
	{
		$this->_pagination = Pagination::factory();
	}

	/**
	 * Поля раздела, которые отображаются в списке
	 * 
	 * @return array Fields array([Field name] => array('name' => [Field header]))
	 */
	public function fields()
	{
		return array(
			'id' => array(
				'name' => 'ID',
				'width' => 50,
				'class' => 'text-right text-muted',
				'visible' => TRUE
			),
			'header' => array(
				'name' => 'Header',
				'width' => NULL,
				'type' => 'link',
				'visible' => TRUE
			)
		);
	}
	
	/**
	 * Рендер View спсика документов раздела
	 * 
	 * @param type $template Путь для своего шаблона
	 * @return View
	 */
	public function render($template = NULL)
	{
		if($template === NULL)
		{
			$template = 'datasource/' . $this->_section->type() . '/headline';
		}
		
		if(Kohana::find_file('views', $template) === FALSE)
		{
			$template = 'datasource/section/headline';
		}

		return View::factory($template, array(
			'fields' => $this->fields(),
			'data' => $this->get(),
			'pagination' => $this->pagination(),
			'datasource' => $this->_section
		));
	}
	
	/**
	 * Рендер View спсика документов раздела
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->render();
	}
	
	/**
	 * Геттер и Сеттер лимита
	 * 
	 * @param integer $limit
	 * @return integer
	 */
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
	
	/**
	 * Возвращает кол-во пропускаемых документов
	 * 
	 * @param integer $limit
	 * @return integer
	 */
	public function offset()
	{
		return (int) $this->_offset;
	}
	
	/**
	 * 
	 * @param integer $num
	 */
	public function set_page($num)
	{
		$this->_page = (int) $num;
		
		return $this;
	}
	
	public function set_query_params()
	{
		$_GET['ds_id'] = $this->_section->id();
	}

	/**
	 * Формирование данных для постраничной навигации
	 * 
	 * @param array $ids
	 * @param string $search_word
	 * @return Pagination
	 */
	public function pagination(array $ids = NULL)
	{
		$this->set_query_params();

		$options = array(
			'items_per_page' => $this->limit(),
			'total_items' => $this->count_total($ids),
			'current_page' => array(
				'source' => 'query_string',
				'key' => 'page',
				'uri' => Route::get('datasources')->uri()
			)
		);

		if (!empty($this->_page))
		{
			$options['current_page']['page'] = $this->_page;
		}

		$this->_pagination->setup($options);

		$this->_offset = (int) $this->_pagination->offset;

		return $this->_pagination;
	}

	/**
	 * Получение списка документов в виде массива
	 * 
	 * 
	 * @param array $ids
	 * @return array array( 'total' => ..., 'documents' => array([id] => array([Field name] => $value, ....)))
	 */
	abstract public function get( array $ids = NULL );
	
	/**
	 * Подсчет кол-ва документов в разделе
	 * 
	 * @param array $ids
	 * @param string $search_word
	 * @return integer
	 */
	abstract public function count_total( array $ids = NULL );
	
	/**
	 * Метод используется для поиска по документам по ключевому слову.
	 * 
	 * Ключевое слово передается в качестве $_GET запроса с ключем "keyword"
	 * 
	 * @param Database_Query $query
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
	
	/**
	 * 
	 * @param Datasource_Section $section
	 * @return \Datasource_Section_Headline
	 */
	public function set_section(Datasource_Section $section)
	{
		$this->_section = $section;
		
		return $this;
	}
	
	/**
	 * Указание порядка сортировки
	 *
	 * @param array $orders array(array([FIELD NAME] => [ASC], ...))
	 * @return \Datasource_Section_Headline
	 */
	public function set_sorting( array $orders = NULL )
	{
		$this->_sorting = $orders;
		return $this;
	}

	/**
	 * 
	 * @return array
	 */
	public function sorting()
	{
		return (array) $this->_sorting;
	}

	public function __sleep()
	{
		return array_keys($this->_serialize());
	}

	protected function _serialize()
	{
		$vars = get_object_vars($this);
		unset(
			$vars['_section'], 
			$vars['_pagination'],
			$vars['_page'],
			$vars['_offset']
		);
		
		return $vars;
	}
	
	public function __wakeup()
	{
		$this->_pagination = Pagination::factory();
	}
}