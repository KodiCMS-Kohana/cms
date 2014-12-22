<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Plugins
 * @category	Drivers
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Plugin_Type_Config extends Plugin_Settings_Decorator {
	
	/**
	 * Сохранение параметров плагина в БД в сериализованном виде
	 * 
	 * @return \Plugin_Decorator
	 */
	public function save_settings()
	{
		Config::set_from_array(array(
			$this->_config_group_key() => $this->settings()
		));

		return $this;
	}
	
	/**
	 * Загрузка параметров плагина из БД
	 *
	 * @return \Plugin_Decorator
	 */
	protected function _load_settings()
	{
		$this->_settings = Config::get($this->_config_group_key())->as_array();

		return $this;
	}
	
	protected function _on_deactivate() 
	{
		DB::delete('config')
			->where('group_name', '=', $this->_config_group_key())
			->execute();
	}
	
	protected function _config_group_key()
	{
		return 'plugin_' . $this->id();
	}
}