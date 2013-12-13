<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Users
 * @category	API
 * @author		ButscHSter
 */
class Controller_API_Email_Types extends Controller_System_Api {

	public function get_options()
	{		
		$uid = $this->param('uid', NULL, TRUE);

		$options = ORM::factory('email_type', (int) $uid)->data();

		$options = Arr::merge($options, Config::get('email', 'default_template_data', array()));
		$this->response($options);
	}
}