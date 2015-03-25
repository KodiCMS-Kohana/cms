<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Calendar
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_API_Calendar extends Controller_System_API {
	
	public function rest_get()
	{
		$start = $this->param('start', NULL, TRUE);
		$end = $this->param('end', NULL, TRUE);
		
		$events = DB::select()
			->from('calendar')
			->where(DB::expr('DATE(start)'), 'between', array($start, $end))
			->where_open()
				->or_where('user_id', '=', Auth::get_id())
				->or_where('user_id', '=', 0)
			->where_close()
			->execute()
			->as_array();
		
		$this->response($events);
	}
	
	public function rest_post()
	{
		$id = (int) $this->param('id', NULL, TRUE);
		$array = $this->params();
		$columns = array(
			'start' ,'end', 'title', 'icon', 'className'
		);
		
		$user_id = DB::select('user_id')
			->from('calendar')
			->where('id', '=', $id)
			->execute()
			->get('user_id');
		
		if ($user_id == 0 OR $user_id == Auth::get_id())
		{
			foreach ($array as $key => $value)
			{
				if (!in_array($key, $columns))
				{
					unset($array[$key]);
				}
			}

			$status = DB::update('calendar')
				->set($array)
				->where('id', '=', $id)
				->execute();
		}
		else
		{
			$this->message('No access');
			$status = FALSE;
		}
		
		$this->response((bool) $status);
	}
	
	public function rest_put()
	{
		$start = $this->param('start', NULL, TRUE);
		$end = $this->param('end', NULL);
		$title = $this->param('title', NULL, TRUE);
		$icon = $this->param('icon', NULL, TRUE);
		$class_name = $this->param('className', NULL, TRUE);
		$private = (bool) $this->param('private', FALSE);
		
		$data = array(
			'start' => $start,
			'end' => $end,
			'title' => $title,
			'icon' => $icon,
			'className' => $class_name,
		);
		
		if ($private === TRUE)
		{
			$data['user_id'] = Auth::get_id();
		}

		list($id, $count) = DB::insert('calendar')
			->columns(array_keys($data))
			->values($data)
			->execute();
		
		$this->response($id);
	}
	
	public function rest_delete()
	{
		$id = (int) $this->param('id', NULL, TRUE);

		$user_id = DB::select('user_id')
			->from('calendar')
			->where('id', '=', $id)
			->execute()
			->get('user_id');
			
		if ($user_id == 0 OR $user_id == Auth::get_id())
		{
			$status = DB::delete('calendar')
				->where('id', '=', $id)
				->execute();
		}
		else
		{
			$this->message('No access');
			$status = FALSE;
		}
		
		$this->response((bool) $status);
	}
}
