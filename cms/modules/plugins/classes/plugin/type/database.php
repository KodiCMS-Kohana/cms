<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Plugins
 * @category	Drivers
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
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
				'settings' => Kohana::serialize($this->settings())
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
			? Kohana::unserialize($settings) 
			: array();
		
		return $this;
	}
	
	protected function _clear_cache()
	{
		if(Kohana::$caching === TRUE)
		{
			Cache::instance()->delete('Database::cache('.Plugin::CACHE_KEY . '::plugin::' . $this->id() . ')');
		}

		return parent::_clear_cache();
	}
	
}