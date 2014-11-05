<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Controller_API_Tags extends Controller_System_API {

	public function rest_get()
	{
		$name = $this->param('term', NULL, TRUE);
		
		if(empty($name))
		{
			return NULL;
		}

		$tags = Model_Tag::findAllLike($name);
		
		$array = array();
		
		foreach ( $tags as $tag )
		{
			$array[] = array(
				'id' => $tag->name,
				'text' => $tag->name
			);
		}
		
		$this->response($array);
	}
}