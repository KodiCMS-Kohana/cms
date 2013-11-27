<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/API
 * @category	Controller
 * @author		ButscHSter
 */
class Controller_API_Typograf extends Controller_System_API {
	
	public function rest_get()
	{
		$text = $this->param('text', NULL, TRUE);
		
		if(empty($text)) return;
		
		include_once Kohana::find_file('vendors', 'emt');
		
		$typograf = new EMTypograph();
		$typograf->set_text($text);
		$result = $typograf->apply();
		
		$this->response($result);
	}
}