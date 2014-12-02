<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Logs
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Log extends ORM {

	const CACHE_TAG = 'logs';

	/**
	 *
	 * @var array 
	 */
	protected $_sorting = array(
		'created_on' => 'desc'
	);
	
	/**
	 *
	 * @var array 
	 */
	protected $_serialize_columns = array(
		'additional'
	);
	
	/**
	 *
	 * @var array 
	 */
	protected $_load_with = array(
		'user'
	);
	
	/**
	 *
	 * @var array 
	 */
	protected $_belongs_to = array(
		'user' => array(
			'model' => 'user'
		)
	);
	
	public function filter()
	{
		$request = Request::initial();
		
		$levels = (array) $request->query('level');
		
		if(!empty($levels))
		{
			$this->where('level', 'in', $levels);
		}
		
		$date_range = $request->query('created_on');
		if(empty($date_range))
		{
			$request->query('created_on', array(
				date('Y-m-d', strtotime("-1 month")), date('Y-m-d')
			));
		}
		
		if(is_array($date_range))
		{
			$this->where(DB::expr('DATE(created_on)'), 'between', $date_range);
		}
		else if(Valid::date($date_range))
		{
			$this->where(DB::expr('DATE(created_on)'), '=', $date_range);
		}
		
		return $this;
	}

	/**
	 * 
	 * @return string
	 */
	public function level()
	{
		return Log::level($this->level);
	}
	
	/**
	 * 
	 * @return string
	 */
	public function user()
	{
		if(empty($this->user_id))
		{
			return NULL;
		}

		return HTML::anchor(Route::get('backend')->uri(array(
			'controller' => 'users', 
			'action' => 'profile', 
			'id' => $this->user->id
		)), $this->user->username);
	}
	
	/**
	 * 
	 * @return string
	 */
	public function url()
	{
		return Arr::get($this->additional, ':url');
	}
	
	/**
	 * 
	 * @return string
	 */
	public function ip()
	{
		$ip =  Arr::get($this->additional, ':ip');
		
		return empty($ip) ? NULL : UI::label($ip, 'default');
	}
	
	/**
	 * 
	 * @param string $interval
	 * @return Database_Query
	 */
	public function clean_old($all = FALSE)
	{
		$query = DB::delete($this->table_name());

		if ($all !== TRUE)
		{
			if ($all === FALSE)
			{
				$all = 'INTERVAL 1 MONTH';
			}

			$query->where(DB::expr('DATE(created_on)'), '<', DB::expr('CURDATE() - ' . $all));
		}
		
		Cache::instance()->delete_tag(self::CACHE_TAG);

		return $query->execute($this->_db);
	}
}