<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class KodiCMS_Controller_Setting extends Controller_System_Backend {

	public function action_index()
	{
		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_save();
		}

		$this->template->content = View::factory( 'setting/index', array(
			'filters' => Arr::merge(array('--none--'), Filter::findAll()),
			'dates' => Date::formats()
		) );
		
		$this->template->title = __('Settings');
		$this->breadcrumbs
			->add($this->template->title, 'setting');
	}

	private function _save()
	{
		$data = $this->request->post('setting');

		if ( !isset( $data['allow_html_title'] ) )
		{
			$data['allow_html_title'] = 'off';
		}
		
		Setting::saveFromData( $data );

		Observer::notify( 'save_settings', $this->request->post() );

		Kohana::$log->add(Log::INFO, 'Change settings')->write();
		
		Messages::success( __( 'Settings has been saved!' ) );

		$this->go_back();
	}
	
	public function action_clear_cache()
	{
		$this->auto_render = FALSE;
		
		Cache::instance()->delete_all();
		Kohana::cache('Kohana::find_file()', NULL, -1);
		Kohana::cache('Route::cache()', NULL, -1);
		Kohana::cache('profiler_application_stats', NULL, -1);
		
		Kohana::$log->add(Log::INFO, 'Clear cache')->write();
		
		Messages::success( __( 'Cache cleared' ) );
		$this->go_back();
	}
}