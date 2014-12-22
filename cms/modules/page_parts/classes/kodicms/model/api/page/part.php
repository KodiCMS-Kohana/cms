<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Page_Parts
 * @category	Model/Api
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Model_API_Page_Part extends Model_API {
	
	protected $_table_name = 'page_parts';
	protected $_secured_columns = array(
		'content', 'content_html'
	);

	public function get_all($page_id, $fields = array())
	{
		$fields = $this->prepare_param($fields);

		$parts = DB::select('id', 'name')
			->select_array($this->filtered_fields($fields))
			->from($this->table_name())
			->where('page_id', '=', (int) $page_id)
			->order_by('position')
			->cache_tags(array('page_parts'))
			->cached((int) Config::get('cache', 'page_parts'))
			->execute()
			->as_array();

		$is_developer = (int) Auth::has_permissions('administrator, developer');

		foreach ($parts as & $part)
		{
			$part['is_developer'] = $is_developer;
		}

		return $parts;
	}

}