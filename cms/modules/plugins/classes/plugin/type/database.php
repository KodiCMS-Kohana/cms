<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Plugins
 * @author		ButscHSter
 */
class Plugin_Type_Database extends Plugin_Settings_Decorator {
	
	/**
	 * Сохранение параметров плагина в БД в сериализованном виде
	 * 
	 * @return \Plugin_Decorator
	 */
	public function save_settings()
	{
		$status = (bool) DB::update( Plugin::TABLE_NAME )
			->set(array(
				'settings' => serialize($this->settings())
			))
			->where('id', '=', $this->id())
			->execute();

		return $this->_clear_cache();
	}
	
	/**
	 * Загрузка параметров плагина из БД
	 *
	 * @return \Plugin_Decorator
	 */
	protected function _load_settings()
	{
		$settings = DB::select('settings')
			->from( Plugin::TABLE_NAME )
			->where('id', '=', $this->id())
			->cache_key(Plugin::CACHE_KEY . '::plugin::' . $this->id())
			->cached(Date::DAY)
			->limit(1)
			->execute()
			->get('settings');
		
		$this->_settings = ! empty($settings) 
			? unserialize($settings) 
			: array();
		
		return $this;
	}
	
	protected function _clear_cache()
	{
		if(Kohana::$caching === TRUE)
		{
			$cache->delete('Database::cache('.Plugin::CACHE_KEY . '::plugin::' . $this->id() . ')');
		}

		return parent::_clear_cache();
	}
	
}