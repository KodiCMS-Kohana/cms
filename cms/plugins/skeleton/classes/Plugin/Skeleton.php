<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * Класс он необходим в том случае, если вам
 * необходимо проводить манипуляции с данными в момент сохранения настроек или
 * изменения поведения плагина в системе
 * 
 * Наличие данного файла не обязательно
 * 
 * @package		KodiCMS/Skeleton
 * @category	Plugin
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Plugin_Skeleton extends Plugin_Decorator {
	
	/**
	 * Правила валидации параметров плагина
	 * 
	 * @return array
	 */
	public function rules()
	{
		return array();
	}
	
	/**
	 * Заголовки параметров
	 * 
	 * @return array
	 */
	public function labels()
	{
		return array();
	}
	
	/**
	 * Правила фильтрации настроек
	 * @return array
	 */
	public function filters()
	{
		return array();
	}
	
	/**
	 * Параметры плагина по умолчанию
	 * 
	 * Доступ к параметрам 
	 * 
	 *		$plugin = Plugins::get('skeleton');
	 *		$plugin->get('param') или $plugin->param
	 * 
	 * @return array
	 */
	public function default_settings()
	{
		$settings = parent::default_settings();
		
		$settings['param'] = '...';
		$settings['param1'] = '...';
		
		return $settings;
	}
	
	/**
	 * 
	 * @param array $data
	 * @return \Plugin_Decorator
	 */
	public function set_settings(array $data)
	{
		if (!isset($data['param']))
		{
			$data['param'] = '...';
		}

		return parent::set_settings($data);
	}
}