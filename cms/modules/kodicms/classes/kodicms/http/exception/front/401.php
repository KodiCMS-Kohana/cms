<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class KodiCMS_HTTP_Exception_Front_401 extends Kohana_HTTP_Exception_401 
{
	/**
	* Generate a Response for the 401 Exception.
	* 
	* The user should be redirect to a login page.
	* 
	* @return Response
	*/
	public function get_response()
	{
		Flash::set('protected_page', Context::instance()->get_page());

		if(($page = Model_Page_Front::findByField('behavior_id', 'protected_page')) !== FALSE)
		{
			return Request::factory($page->url)->execute();
		}

		throw new HTTP_Exception_401($this->message);
	}
}