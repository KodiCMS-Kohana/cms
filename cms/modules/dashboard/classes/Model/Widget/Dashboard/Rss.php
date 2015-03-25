<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Dashboard
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Dashboard_RSS extends Model_Widget_Decorator_Dashboard {	
	
	/**
	 *
	 * @var boolean
	 */
	protected $_multiple = TRUE;

	protected $_data = array(
		'limit' => 10,
		'height' => 250
	);
	
	public function set_rss_url($url) 
	{
		if (!Valid::url($url))
		{
			return NULL;
		}

		return $url;
	}
	
	public function set_limit($limit) 
	{
		return (int) $limit;
	}
	
	public function set_height($height) 
	{
		return (int) $height;
	}
	
	/**
	 * 
	 * @return array [$rss_url, $items]
	 */
	public function fetch_data()
	{
		$cache = Cache::instance();
		
		$data = $cache->get($this->id);
		if (empty($data))
		{
			$data = Feed::parse($this->rss_url, $this->limit);
			$cache->set($this->id, $data, Date::MINUTE * 10);
		}

		$data['rss_url'] = $this->rss_url;
		
		return $data;
	}
}