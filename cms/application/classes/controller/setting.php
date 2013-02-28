<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Setting extends Controller_System_Backend {

	public function action_index()
	{
		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_save();
		}

		$dates = array();
		foreach (Kohana::$config->load('global')->get('date_formats', array()) as $format)
		{
			$dates[$format] = Date::format(time(), $format);
		}

		$this->template->content = View::factory( 'setting/index', array(
			'filters' => Arr::merge(array('--none--'), Filter::findAll()),
			'dates' => $dates
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
		
		Observer::notify( 'save_settings', $this->request->post() );

		Setting::saveFromData( $data );

		Messages::success( __( 'Settings has been saved!' ) );

		$this->go( 'setting' );
	}

}// end SettingController class