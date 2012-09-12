<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Redirect extends Controller_System_Plugin
{
	public function action_settings()
	{
		if ( Request::current()->method() == Request::POST )
		{
			$settings = array(
				'check_url_suffix' => 'no',
			);

			$post_settings = Arr::get($_POST, 'setting', array());
			foreach ($post_settings as $key => $val)
			{
				$settings[$key] = $val;
			}
			
			Plugins::setAllSettings($settings, 'redirect');
			
			Messages::success( __('Settings has been saved!'));
			$this->go(URL::site('redirect/settings'));
		}
		
		$this->template->content = View::factory('redirect/settings', array(
			'settings' => Plugins::getAllSettings('redirect')
		));
	}

}