<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Setting extends Controller_System_Backend {

	public function action_index()
	{
		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_save();
		}

		$this->template->content = View::factory( 'setting/index', array(
			'filters' => Filter::findAll(),
			'loaded_filters' => Filter::$filters
		) );
	}

	private function _save()
	{
		$data = $_POST['setting'];

		if ( !isset( $data['allow_html_title'] ) )
		{
			$data['allow_html_title'] = 'off';
		}
		
		Observer::notify( 'save_settings', $_POST );

		Setting::saveFromData( $data );

		Messages::success( __( 'Settings has been saved!' ) );

		$this->go( URL::site( 'setting' ) );
	}

}// end SettingController class