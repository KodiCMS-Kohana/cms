<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_System_Download extends Controller {
	
	public $auto_render = FALSE;

	public function action_index()
	{
		$path = $this->request->param('path');
		$path = Download::decode_path($path);
		
		$full_path = DIRECTORY_SEPARATOR . trim($path, '/');

		if( ! file_exists( $full_path ))
		{
			if(IS_BACKEND)
			{
				throw new HTTP_Exception_404('File :file not found', array(
					':file' => $path));
			}
			else
			{
				Model_Page_Front::not_found('File :file not found', array(
					':file' => $path));
			}
		}
		
		$this->response
			->send_file($full_path);
	}
	
}