<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Maintenance
 * @category	Plugin
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Plugin_Maintenance extends Plugin_Decorator {
	
	public function default_settings()
	{
		$settings = parent::default_settings();
		
		$settings['maintenance_mode'] = Config::NO;
		return $settings;
	}
}