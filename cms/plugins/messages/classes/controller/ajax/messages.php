<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_Ajax_Messages extends Controller_Ajax_JSON {

	public function action_users_list()
	{
		$username = $this->request->query('username');
		
		$users = DB::select('id', 'username')
			->from('users')
			->where('username', 'like', $username. '%')
			->or_where('name', 'like', $username. '%')
			->or_where('email', 'like', $username. '%')
			->execute()
			->as_array(NULL, 'username');
		
		$this->json['status'] = TRUE;
		$this->json['data'] = $users;
	}
}