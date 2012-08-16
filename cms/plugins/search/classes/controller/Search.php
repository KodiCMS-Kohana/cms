<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Search extends Controller_System_Plugin
{
	
	public function action_settings()
	{
		if ( Request::current()->method() == Request::POST )
		{
			$settings = Arr::get($_POST, 'setting', array());
			Plugins::setAllSettings($settings, 'search');
			
			Messages::success( __('Settings has been saved!'));
			$this->go(URL::site('plugin/search/settings'));
		}
		
		$this->template->content = View::factory('search/settings', array(
			'settings' => Plugins::getAllSettings('search')
		));
	}

}