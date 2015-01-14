<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Search
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_API_Search extends Controller_System_Api {
	
	public function get_update_index()
	{		
		Observer::notify('update_search_index');
		$this->message('Search index updated');
	}
	
	public function get_keyword()
	{		
		$keyword = $this->param('keyword', NULL, TRUE);
		$modules = $this->param('modules', NULL);
		$driver = $this->param('driver', NULL);
		
		$data = Search::instance($driver)->find_by_keyword($keyword, FALSE, $modules);
		$this->response($data);
	}
}