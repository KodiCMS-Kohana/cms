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
	 * @param array $array
	 */
	public function __construct( array $array = array())
	{
		$this->_array = $array;
	}
	
	/**
	 * Поиск страницы по ID
	 * 
	 * @param integer $id
	 * @return \KodiCMS_Sitemap
	 */
	public function find( $id )
	{
		$this->_array = $this->_find( $this->_array, 'id', $id );
		
		return $this;
	}
	
	/**
	 * 
	 * @param string $key
	 * @param string $value
	 * @return \KodiCMS_Sitemap
	 */
	public function find_by( $key, $value )
	{
		$this->_array = $this->_find( $this->_array, $key, $value );
		
		return $this;
	}
	
	/**
	 * Фильтрация массива
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @return \KodiCMS_Sitemap
	 */
	public function filter($key, $value)
	{
		$this->_filter($this->_array, $key, $value);

		return $this;
	}

	/**
	 * Получение внутренних страниц относительно текущей
	 * 
	 * @return \KodiCMS_Sitemap
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
	 * @param boolean $remove_childs
	 * @return \KodiCMS_Sitemap
	 */
	public function exclude( array $ids, $remove_childs = TRUE )
	{
		if( !empty($ids) )
			$this->_exclude( $this->_array, $ids, $remove_childs );

		return $this;
	}

	/**
	 * Вывов спсика страниц в виде массива
	 * 
	 * @param boolean $childs Показывать дочерние эелементы 
	 * @return array
	 */
	public function as_array( $childs = TRUE )
	{
		if( $childs === FALSE )
		{
			foreach($this->_array as & $row)
			{
				if(isset($row['childs']))
					unset( $row['childs'] );
			}
		}
			
		return $this->_array;
	}

	/**
	 * Сделать список страниц плоским
	 * 
	 * @param boolean $childs Показывать дочерние эелементы 
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
		if( isset($this->_array[0]) )
		{
			return array_reverse($this->_breadcrumbs( $this->_array[0] ));
		}
		
		return array();
	}
	
	/**
	 * Получить список страниц для выпадающего списка <select>
	 * 
	 * @param string $title_key
	 * @param boolean $level
	 * @param string $empty_value
	 * @return array
	 */
	public function select_choices($title_key = 'title', $level = TRUE, $empty_value = FALSE)
	{
		$array = $this->flatten();
		
		$options = array();
		
		if($empty_value !== FALSe)
		{
			$options[] = $empty_value;
		}
		
		foreach ($array as $row)
		{
			if($level === TRUE)
			{
				$level_string = str_repeat('- ', Arr::get($row, 'level', 0) * 2);
			}
			else
			{
				$level_string = '';
			}
			$options[$row['id']] = $level_string . $row[$title_key];
		}
		
		return $options;
	}
	
	/**
	 * 
	 * @param array $array
	 * @param integer $id
	 * @return array
	 */
	protected function _find( $array, $key, $value )
	{
		$found = array();
		foreach($array as $row)
		{
			if($row[$key] == $value)
			{
				return array($row);
			}
			
			if( ! empty($row['childs']))
			{
				$found = $this->_find($row['childs'], $key, $value);
				
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
	 * @param array $data
	 * @param array $crumbs
	 * @return type
	 */
	protected function _breadcrumbs( array $data, &$crumbs = array() )
	{
		$crumbs[] = $data;
			
		if( !empty($data['parent']) )
			$this->_breadcrumbs( $data['parent'], $crumbs );
		
		return $crumbs;
	}
	
	/**
	 * 
	 * @param array $array
	 * @param array $ids
	 * @param boolean $remove_childs
	 */
	protected function _exclude( & $array, array $ids, $remove_childs = TRUE )
	{
		foreach($array as $i => & $row)
		{
			if( in_array($row['id'], $ids) )
			{
				unset($array[$i]);
				
				if($remove_childs !== TRUE AND ! empty($row['childs']))
				{
					foreach($row['childs'] as $child)
					{
						$array[] = $child;
					}
				}
			}
			
			if( ! empty($row['childs']) )
			{
				$this->_exclude($row['childs'], $ids, $remove_childs);
			}
		}
	}
	
	/**
	 * 
	 * @param array $array
	 * @param string $key
	 * @param mixed $value
	 */
	protected function _filter( & $array, $key, $value )
	{
		foreach($array as $i => $row)
		{
			if(isset($row[$key]) AND $row[$key] == $value)
				unset($array[$i]);
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
		foreach( $array as $row )
		{
			$return[$row['id']] = $row;
			
			if( $childs !== FALSE AND !empty($row['childs']))
			{
				$this->_flatten( $row['childs'], $childs, $return );
			}
			
			unset($return[$row['id']]['childs']);
		}
		
		return $return;
	}
}