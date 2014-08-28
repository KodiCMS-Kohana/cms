<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Calendar
 * @category	Controller
 * @author		ButscHSter
 */
class Controller_API_Calendar extends Controller_System_API {
	
	public function rest_get()
	{
		$start = $this->param('start', NULL, TRUE);
		$end = $this->param('end', NULL, TRUE);
		
		$events = DB::select()
			->from('calendar')
			->where(DB::expr('DATE(start)'), 'between', array($start, $end))
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
		
		foreach ($array as $key => $value)
		{
			if(!in_array($key, $columns))
			{
				unset($array[$key]);
			}
		}

		$status = DB::update('calendar')
			->set($array)
			->where('id', '=', $id)
			->execute();
		
		$this->response((bool) $status);
	}
	
	public function rest_put()
	{
		$start = $this->param('start', NULL, TRUE);
		$end = $this->param('end', NULL);
		$title = $this->param('title', NULL, TRUE);
		$icon = $this->param('icon', NULL, TRUE);
		$class_name = $this->param('className', NULL, TRUE);
		
		$data = array(
			'start' => $start,
			'end' => $end,
			'title' => $title,
			'icon' => $icon,
			'className' => $class_name
		);

		list($id, $count) = DB::insert('calendar')
			->columns(array_keys($data))
			->values($data)
			->execute();
		
		$this->response($id);
	}
	
	public function rest_delete()
	{
		$id = (int) $this->param('id', NULL, TRUE);

		$status = DB::delete('calendar')
			->where('id', '=', $id)
			->execute();
		
		$this->response((bool) $status);
	}
}
