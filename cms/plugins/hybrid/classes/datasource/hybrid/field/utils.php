<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Utils {
	
	/**
	 *
	 * @var array 
	 */
	protected static $_cached_headers = array();
	
	/**
	 * 
	 * @param integer $ds_id
	 * @return Datasource_Section
	 */
	public static function load_ds($ds_id)
	{
		return Datasource_Data_Manager::load($ds_id);
	}
	
	/**
	 * 
	 * @param string $type
	 * @param integer $ds_id
	 * @param integer $id
	 * @return type
	 */
	public static function get_document_header($type, $ds_id, $id) 
	{
		if(isset(self::$_cached_headers[$ds_id][$id]))
		{
			return self::$_cached_headers[$ds_id][$id];
		}

		return self::_query_document_headers($type, $ds_id, array($id))->get('header');
	}
	
	/**
	 * 
	 * @param string $type
	 * @param integer $ds_id
	 * @param array $ids
	 * @return array
	 */
	public static function get_document_headers( $type, $ds_id, array $ids ) 
	{
		$result = array();

		if(!empty($ids)) 
		{
			$result = self::_query_document_headers($type, $ds_id, $ids)->as_array('id', 'header');

			foreach ($result as $id => $header )
			{
				self::$_cached_headers[$ds_id][$id] = $header;
			}
		}

		return $result;
	}
	
	/**
	 * 
	 * @param string $type
	 * @param integer $ds_id
	 * @param array $ids
	 * @return Database_Result
	 */
	protected static function _query_document_headers($type, $ds_id, array $ids) 
	{
		return DB::select('dshybrid.id', 'dshybrid.header')
			->from(array('ds' . $type . '_' . $ds_id, 'ds'))
			->join('dshybrid', 'left')
				->on('dshybrid.id', '=', 'ds.id')
			->where('ds.id', 'in', $ids)
			->execute();
	}
}