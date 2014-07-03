<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Update extends Controller_System_API {
	
	public function get_check_version()
	{
		$this->response(Update::check_version() === Update::VERSION_OLD);
	}
	
	public function get_check_files()
	{
		$this->response((string) View::factory('update/files', array(
			'files' => Update::check_files()
		)));
	}
}