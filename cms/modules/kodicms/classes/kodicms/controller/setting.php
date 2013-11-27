<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Controller
 * @author		ButscHSter
 */
class KodiCMS_Controller_Setting extends Controller_System_Backend {

	public function before()
	{
		parent::before();
		$this->breadcrumbs
			->add(__('Settings'), 'backend/setting');
	}
	
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
	}

	private function _save()
	{
		$data = $this->request->post('setting');

		if ( !isset( $data['site']['allow_html_title'] ) )
		{
			$data['site']['allow_html_title'] = 'off';
		}
		
		Config::set_from_array($data);

		Observer::notify( 'save_settings', $this->request->post() );

		Kohana::$log->add(Log::INFO, 'Change settings')->write();
		
		Messages::success( __( 'Settings has been saved!' ) );

		$this->go_back();
	}
}