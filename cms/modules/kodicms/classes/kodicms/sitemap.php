<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		ButscHSter
 */
class KodiCMS_Sitemap
{
	/**
	 * Список страниц
	 * 
	 * @var array 
	 */
	protected $_array = array();

	/**
	 * 
	 * @param array $pages
	 */
	public function __construct( array $pages = array())
	{
		$this->_array = $pages;
	}
	
	/**
	 * Поиск страницы по ID
	 * 
	 * @param integer $id
	 * @return \Model_Page_Sitemap
	 */
	public function find( $id )
	{
		$this->_array = $this->_find( $this->_array, $id );
		
		return $this;
	}
	/**
	 * Получение внутренних страниц относительно текущей
	 * 
	 * @return \Model_Page_Sitemap
	 */
	public function children()
	{
		if( ! empty($this->_array[0]['childs']))
		{
			$this->_array = $this->_array[0]['childs'];
		}
		else
		{
			$this->_array = array();
		}
		
		return $this;
	}
	
	/**
	 * Исключение из карты сайта страниц по ID
	 * 
	 * @param array $ids
	 * @return \Model_Page_Sitemap
	 */
	public function exclude( array $ids )
	{
		if( !empty($ids) )
			$this->_exclude( $this->_array, $ids );

		return $this;
	}

	/**
	 * Вывов спсика страниц в виде массива
	 * 
	 * @param boolean $childs Выводить внутренние страницы
	 * @return array
	 */
	public function as_array( $childs = TRUE )
	{
		if( $childs === FALSE )
		{
			foreach($this->_array as & $page)
			{
				if(isset($page['childs']))
					unset( $page['childs'] );
			}
		}
			
		return $this->_array;
	}

	/**
	 * Сделать список страниц плоским
	 * 
	 * @return array
	 */
	public function flatten( $childs = TRUE )
	{
		return $this->_flatten( $this->_array, $childs );
	}
	
	/**
	 * Получить хлебные крошки для текущей страницы
	 * 
	 * @return array
	 */
	public function breadcrumbs()
	{
		return array_reverse($this->_breadcrumbs( $this->_array[0] ));
	}
	
	/**
	 * Получить список страниц для выпадающего списка <select>
	 * 
	 * @return array
	 */
	public function select_choices()
	{
		$pages = $this->flatten();
		
		$options = array();
		foreach ($pages as $page)
		{
			$options[$page['id']] = str_repeat('- ', $page['level'] * 2) . $page['title'];
		}
		
		return $options;
	}
	
	/**
	 * 
	 * @param array $array
	 * @param integer $id
	 * @return array
	 */
	protected function _find( $array, $id )
	{
		$found = array();
		foreach($array as $page)
		{
			if($page['id'] == $id)
			{
				return array($page);
			}
			
			if( ! empty($page['childs']))
			{
				$found = $this->_find($page['childs'], $id);
				
				if(!empty($found)) 
				{
					return $found;
				}
			}
		}
		
		return $found;
	}
	
	/**
	 * 
	 * @param array $page
	 * @param array $crumbs
	 * @return type
	 */
	protected function _breadcrumbs( array $page, &$crumbs = array() )
	{
		$crumbs[] = $page;
			
		if( !empty($page['parent']) )
			$this->_breadcrumbs( $page['parent'], $crumbs );
		
		return $crumbs;
	}
	
	/**
	 * 
	 * @param array $array
	 * @param array $ids
	 * @return array
	 */
	protected function _exclude( & $array, array $ids )
	{
		foreach($array as $i => & $page)
		{
			if( in_array($page['id'], $ids) )
			{
				unset($array[$i]);
			}
			
			if( !empty($page['childs']))
			{
				$this->_exclude($page['childs'], $ids);
			}
		}
	}
	
	/**
	 * 
	 * @param array $array
	 * @param boolean $childs
	 * @param array $return
	 * @return array
	 */
	protected function _flatten( array $array, $childs = TRUE, & $return = array() )
	{
		foreach( $array as $page )
		{
			$return[$page['id']] = $page;
			
			if( $childs !== FALSE AND !empty($page['childs']))
			{
				$this->_flatten( $page['childs'], $childs, $return );
			}
			
			$return[$page['id']]['childs'] = array();
		}
		
		return $return;
	}
}