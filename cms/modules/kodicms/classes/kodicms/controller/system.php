<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Controller
 * @author		ButscHSter
 */
class KodiCMS_Controller_System extends Controller_System_Backend {

	public function before()
	{
		parent::before();

		$this->breadcrumbs
			->add(__('System'), Route::url('backend', array('controller' => 'system', 'action' => 'information')));
	}
	
	public function action_index()
	{
		return $this->action_information();
	}
	
	public function action_information()
	{
		if (version_compare(PHP_VERSION, '5.3', '<'))
		{
			// Clear out the cache to prevent errors. This typically happens on Windows/FastCGI.
			clearstatcache();
		}
		else
		{
			// Clearing the realpath() cache is only possible PHP 5.3+
			clearstatcache(TRUE);
		}
		
		$this->template->content = View::factory( 'system/information', array(
			'failed' => FALSE
		));
		
		$this->template->title = __('Information');
		$this->breadcrumbs
			->add($this->template->title);
	}
	
	public function action_settings()
	{
		$this->template->content = View::factory( 'system/settings', array(
			'filters' => Arr::merge(array('--none--'), WYSIWYG::findAll()),
			'dates' => Date::formats()
		) );
		
		$this->template->title = __('Settings');
	}
	
	public function action_phpinfo()
	{
		$this->auto_render = FALSE;
		phpinfo();
	}
}