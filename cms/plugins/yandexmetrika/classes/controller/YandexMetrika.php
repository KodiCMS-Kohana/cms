<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_YandexMetrika extends Controller_System_Plugin
{
	
	public function action_settings()
	{
		if ( Request::current()->method() == Request::POST )
		{
			$settings = Arr::get($_POST, 'setting', array());
			Plugins::setAllSettings($settings, 'yandex_metrika');
			
			Messages::success( __('Settings has been saved!'));
			$this->go(URL::site('plugin/yandexmetrika/settings'));
		}
		
		$this->template->content = View::factory('yandexmetrika/settings', array(
			'settings' => Plugins::getAllSettings('yandex_metrika')
		));
	}
}