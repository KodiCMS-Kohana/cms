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

			$post = $this->request->post();
			$post['from_user_id'] = AuthUser::getId();
			$post['to_user_id'] = $user->id;
			return $this->_send(Api::put('messages', $post));
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
		$user_id = AuthUser::getId();

		$message = Api::get('messages.get_by_id', array(
			'id' => $id, 
			'uid' =>  $user_id,
			'fields' => 'author,title,is_read,created_on,text'
		))->as_object();
		
		if( ! $message->response )
		{
			throw new HTTP_Exception_404('Message not found');
		}
		
		if($this->request->method() === Request::POST)
		{
			$post = $this->request->post();
			$post['from_user_id'] = $user_id;
			$post['parent_id'] = $id;
			return $this->_send(Api::put('messages', $post), $id);
		}
		
		Api::post('messages.mark_read', array(
			'id' => $id, 'uid' => $user_id
		));
		
		$new = Api::get('messages.count_new', array(
			'uid' => $user_id
		))->as_object();
		
		Model_Navigation::update(URL::site('messages'), array(
			'counter' => $new->response
		));
		
		$messages = Api::get('messages.get', array(
			'uid' => $user_id, 
			'fields' => 'author,title,is_read,created_on,text',
			'pid' => $id
		))
			->as_object();
		
		$this->template->content = View::factory('messages/view', array(
			'tpl' => View::factory('messages/item'),
			'message' => $message->response,
			'messages' => $messages->response
		));
		
		$this->breadcrumbs
			->add($message->response->title);
	}
	
	private function _send($send, $parent_id = 0)
	{
		$status = $send->as_object()->response;

		if((int) $status > 0)
		{
			$this->go('messages/view/' . (int) $status);
		}
		
		$this->go_back();
	}
}