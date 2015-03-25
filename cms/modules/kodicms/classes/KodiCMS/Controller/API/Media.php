<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Controller_API_Media extends Controller_System_Api {
	
	public function get_images()
	{
		$json = array();

		$module = $this->param('module');

		$images = ORM::factory('media')
			->where('content_type', '=', 'image');

		if ($module !== NULL)
		{
			$images->where('module', '=', $module);
		}

		foreach ($images->find_all() as $image)
		{
			$json[] = array(
				'id' => $image->id,
				'thumb' => Image::cache($image->filename, 100, 100, Image::INVERSE),
				'image' => PUBLIC_URL . $image->filename,
				'title' => (string) $image->description,
				'folder' => $image->module
			);
		}
		
		$this->response($json);
	}
	
	public function post_images()
	{
		$json = array();
		$file = $_FILES['file'];
		
		$module = $this->param('module', 'default');

		if (!Upload::not_empty($file))
		{
			$this->json = json_encode($json);
			return;
		}

		$image = ORM::factory('media')
			->set('module', $module)
			->upload($file, array('jpg', 'jpeg', 'gif', 'png'));
		
		$json = array(
			'id' => $image->id,
			'thumb' => Image::cache($image->filename, 100, 100, Image::INVERSE),
			'image' => PUBLIC_URL . $image->filename,
			'title' => (string) $image->description,
			'folder' => $image->module
		);

		$this->response($json);
	}
}