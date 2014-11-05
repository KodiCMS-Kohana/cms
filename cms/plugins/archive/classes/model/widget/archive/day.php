<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Archive_Day extends Model_Widget_Archive {

	/**
	 * 
	 * @return array [$links]
	 */
	public function fetch_data()
	{
		$page = $this->get_page();

		$result = DB::select(array(DB::expr( 'DATE_FORMAT('. Database::instance()->quote_column('published_on').', "%Y/%m/%d")' ), 'date'))
			->distinct(TRUE)
			->from('pages')
			->where('parent_id', '=', $page->id)
			->where('status_id', '!=', Model_Page::STATUS_HIDDEN)
			->order_by( 'published_on', 'desc' )
			->execute()
			->as_array(NULL, 'date');
		
		$data = array();
		foreach($result as $date)
		{
			$data[] = array(
				'href' => BASE_URL . $page->url .'/'. $date . URL_SUFFIX,
				'title' => strftime('%d %B %Y', strtotime(strtr($date, '/', '-'))),
				'date' => $date
			);
		}

		return array(
			'links' => $data
		);
	}
}