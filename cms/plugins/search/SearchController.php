<?php if (!defined('CMS_ROOT')) die;

class SearchController extends PluginController
{
	public function __construct()
	{
		$this->setLayout('backend');
	}
	
	public function settings()
	{
		if (get_request_method() == 'POST')
		{
			
			Plugin::setAllSettings($_POST['setting'], 'search');
			
			Flash::set('success', __('Settings has been saved!'));
			redirect(get_url('plugin/search/settings'));
		}
		
		$this->display('search/views/settings', array(
			'settings' => Plugin::getAllSettings('search')
		));
	}

}