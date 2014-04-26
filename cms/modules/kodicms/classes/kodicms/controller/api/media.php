<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	API
 * @author		ButscHSter
 */
class KodiCMS_Controller_API_Media extends Controller_System_Api {
	
	public function get_images()
	{
		$json = array();
		$images = ORM::factory('media')
			->where('content_type', '=', 'image')
			->find_all();
		
		foreach ($images as $image)
		{
			$json[] = array(
				'thumb' => Image::cache($image->filename, 100, 100, Image::INVERSE),
				'image' => PUBLIC_URL . $image->filename,
				'title' => (string) $image->description,
				'folder' => $image->module
			);
		}
		
		$this->json = json_encode($json);
	}
	
	public function post_images()
	{
		$json = array();
		$file = $_FILES['file'];
		if( ! Upload::not_empty($file) ) 
		{
			$this->json = json_encode($json);
			return;
		}
		
		$uploaded_file = ORM::factory('media')
			->set('module', 'redactorJS')
			->upload($file, array('jpg', 'jpeg', 'gif', 'png'));
		
		$json = array('filelink' => PUBLIC_URL . $uploaded_file->filename);
		
		$this->json = json_encode($json);
	}
}