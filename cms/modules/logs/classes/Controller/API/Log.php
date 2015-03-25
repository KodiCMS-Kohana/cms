<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Logs
 * @category	Api
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_API_Log extends Controller_System_Api {
	
	public function post_clear_old()
	{
		$all = (bool) $this->param('all', FALSE);
		
		$this->response((bool) ORM::factory('log')->clean_old($all));
		$this->message('Old logs has been deleted');
	}

	public function get_get()
	{		
		$uids = $this->param('uids');
		$level = $this->param('level');
		$from = $this->param('from');
		$to = $this->param('to');
		
		$interval = $this->param('interval');
		
		switch ($interval) 
		{
			case 'last-day': 
				$from = DB::expr('CURDATE() - INTERVAL 1 DAY');
				$to = DB::expr('CURDATE()');
				break;
			case 'last-week':
				$from = DB::expr('CURDATE() - INTERVAL 1 WEEK');
				$to = DB::expr('CURDATE()');
				break;
			case 'last-month':
				$from = DB::expr('CURDATE() - INTERVAL 1 MONTH');
				$to = DB::expr('CURDATE()');
				break;
		}

		$limit = (int) $this->param('limit', 10);

		if (!empty($uids))
		{
			$uids = explode(',', $uids);
		}

		if (!empty($level))
		{
			$level = explode(',', $level);
		}
		else
		{
			$level = array(Log::INFO);
		}

		$list = DB::select('logs.id', 'logs.created_on', 'logs.level', 'logs.message', 'logs.user_id')
			->select(array(DB::expr('COUNT(*)'), 'count'))
			->select('users.email', 'users.username')
			->from('logs')
			->join('users')
				->on('users.id', '=', 'logs.user_id')
			->group_by('logs.message')
			->limit($limit)
			->order_by('created_on', 'asc');
		
		if (!empty($from) AND ! empty($to))
		{
			$list->where(DB::expr('DATE(created_on)'), 'between', array($from, $to));
		}

		if (!empty($uids))
		{
			$list->where('user_id', 'in', $uids);
		}

		if (!empty($level))
		{
			$list->where('level', 'in', $level);
		}

		$list
			->cached(DATE::HOUR)
			->cache_tags(array(Model_Log::CACHE_TAG));

		$data = $list->execute()->as_array('id');
		
		foreach ($data as $id => $log)
		{
			$data[$id]['level'] = Log::level($log['level']);
		}

		$this->response($data);
	}
}