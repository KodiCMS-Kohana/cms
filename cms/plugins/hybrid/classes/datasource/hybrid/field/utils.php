<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Utils {
	
	/**
	 * Кеш заголовков документов
	 * 
	 * @static
	 * @var array 
	 */
	protected static $_cached_headers = array();
	
	/**
	 * @deprecated since 10.0.0
	 * 
	 * @param integer $ds_id
	 * @return Datasource_Section
	 */
	public static function load_ds($ds_id)
	{
		return Datasource_Data_Manager::load($ds_id);
	}
	
	/**
	 * Получение заголовка документа
	 * 
	 * @param integer $ds_id Идентификатор раздела
	 * @param integer $id Идентификатор документа
	 * @return string
	 */
	public static function get_document_header($ds_id, $id)
	{
		if (isset(self::$_cached_headers[$ds_id][$id]))
		{
			return self::$_cached_headers[$ds_id][$id];
		}

		$documents = self::get_document_headers($ds_id, array($id));

		return count($documents) > 0 ? reset($documents) : NULL;
	}

	/**
	 * Получение списка заголовков документов
	 * 
	 * @param integer $ds_id
	 * @param array $ids
	 * @return array
	 */
	public static function get_document_headers($ds_id, array $ids)
	{
		$result = array();
		
		foreach ($ids as $i => $id)
		{
			if (isset(self::$_cached_headers[$ds_id][$id]))
			{
				$result[$id] = self::$_cached_headers[$ds_id][$id];
				unset($ids[$i]);
			}
		}

		if (empty($ids))
		{
			return $result;
		}

		$ds = Datasource_Section::load($ds_id);
		if (!($ds instanceof Datasource_Section))
		{
			return array();
		}

		$db_result = DB::select('id', 'header')
			->from($ds->table())
			->where('id', 'in', $ids)
			->execute()
			->as_array('id', 'header');

		foreach ($db_result as $id => $header)
		{
			self::$_cached_headers[$ds_id][$id] = $header;
			$result[$id] = $header;
		}

		return $result;
	}
}