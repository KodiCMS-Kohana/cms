<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Messages extends Controller_System_Backend {
	
	public function before()
	{
		parent::before();
		$this->breadcrumbs
			->add(__('Messages'), strtolower($this->request->controller()));
	}
	
	public function action_index()
	{		
		$messages = Api::get('messages.get', array('uid' => AuthUser::getId(), 'fields' => 'author,title,is_read,created_on'))
			->as_object();

		$this->template->content = View::factory('messages/index', array(
			'messages' => $messages->response
		));
	}

	public function action_add()
	{
		if($this->request->method() === Request::POST)
		{
			$username = $this->request->post('to');
			$user = ORM::factory('user', array('username' => $username));
			
			if( ! $user->loaded() ) 
			{
				throw new HTTP_Exception_404('User not found');
			}

			return $this->_send($user->id);
		}

		$this->template->content = View::factory('messages/add', array(
			'user_id' => AuthUser::getId()
		));
		
		$this->breadcrumbs
			->add(__('Send message'));
	}
	
	public function action_view()
	{
		$id = (int) $this->request->param('id');
		
		$orm = ORM::factory( 'message');
		$message = $orm->get_one($id, AuthUser::getId());
		
		if( ! $message->loaded())
		{
			throw new HTTP_Exception_404('Message not found');
		}
		
		if($this->request->method() === Request::POST)
		{
			$users = $orm->get_users_by_id($id);
			return $this->_send($users, $id);
		}
		
		$message->mark_read(AuthUser::getId());
		
		Model_Navigation::update(URL::site('messages'), array(
			'counter' => ORM::factory( 'message' )->count_new(AuthUser::getId())
		));
		
		$this->template->content = View::factory('messages/view', array(
			'tpl' => View::factory('messages/item'),
			'message' => $message,
			'messages' => $orm->get_all(AuthUser::getId(), $id)
		));
		
		$this->breadcrumbs
			->add($message->title);
	}
	
	private function _send($users, $parent_id = 0)
	{
		$data = $this->request->post();
		
		$parent_id = (int) $parent_id;
		$title = Arr::get($data, 'title');
		$content = Arr::get($data, 'content');

		if(empty($users))
		{
			return FALSE;
		}
		
		$message_id = ORM::factory( 'message')->send($title, $content, AuthUser::getId(), $users, $parent_id);
		if($parent_id > 0)
		{
			$this->go_back();
		}
		else
		{
			$this->go('messages/view/' . $message_id);
		}
	}
}