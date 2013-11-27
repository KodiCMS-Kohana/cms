<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Log extends Controller_System_Api {
	
	public function get_get()
	{		
		$uids = $this->param('uids');
		$level = $this->param('level');
		
		if(!empty($uids))
			$uids = explode(',', $uids);
		
		if(!empty($level))
			$level = explode(',', $level);

		$list = DB::select('id', 'created_on', 'level', 'message', 'user_id')
			->from('logs')
			->limit(10)
			->order_by('created_on', 'desc');
		
		if(!empty($uids))
		{
			$list->where('user_id', 'in', $uids);
		}
		
		if(!empty($level))
		{
			$list->where('level', 'in', $level);
		}

		$this->response($list->execute()->as_array('id'));
	}
}