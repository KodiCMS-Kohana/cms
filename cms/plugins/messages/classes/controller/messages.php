<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/User_Messages
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Messages extends Controller_System_Backend {
	
	public $allowed_actions = array('index', 'add', 'view');
	
	public function before()
	{
		parent::before();
		$this->breadcrumbs
			->add(__('Messages'), Route::get('backend')->uri(array('controller' => 'messages')));
		
		Assets::package('redactor');
	}
	
	public function action_index()
	{
		$this->set_title(__('Messages'), FALSE);

		$this->template->content = View::factory('messages/index', array(
			'messages' => Api::get('user-messages.get', array(
				'uid' => Auth::get_id(), 
				'fields' => 'author,title,is_read,is_starred,created_on,from_user_id'
			))->as_object()->get('response')
		));
	}

	public function action_add()
	{
		if ($this->request->method() === Request::POST)
		{
			$ids = (array) $this->request->post('to');

			$post = $this->request->post();
			$post['from_user_id'] = Auth::get_id();
			$post['to_user_id'] = $ids;

			return $this->_send(Api::put('user-messages', $post));
		}

		$to = $this->request->query('to');
		$to = ORM::factory('user', $to)->id;

		$this->template->content = View::factory('messages/add', array(
			'user_id' => Auth::get_id(),
			'to' => $to
		));
		
		$this->set_title(__('Send message'));
	}
	
	public function action_view()
	{
		$id = (int) $this->request->param('id');
		$user_id = Auth::get_id();

		$message = Api::get('user-messages.get_by_id', array(
			'id' => $id, 
			'uid' =>  $user_id,
			'fields' => 'author,title,is_read,created_on,text,is_starred'
		))->as_object();
		
		if (!$message->response)
		{
			throw new HTTP_Exception_404('Message not found');
		}

		if ($this->request->method() === Request::POST)
		{
			$this->auto_render = FALSE;
			$post = $this->request->post();
			$post['from_user_id'] = $user_id;
			$post['parent_id'] = $id;

			return $this->_send(Api::put('user-messages', $post), $id);
		}

		$read = Api::post('user-messages.mark_read', array(
			'id' => $id, 'uid' => $user_id
		));
		
		$messages = Api::get('user-messages.get', array(
			'uid' => $user_id, 
			'fields' => 'author,from_user_id,title,is_read,created_on,text,is_starred',
			'pid' => $id
		))->as_object();
		
		$this->template->content = View::factory('messages/view', array(
			'tpl' => View::factory('messages/item'),
			'message' => $message->response,
			'messages' => $messages->response,
			'from_user' => ORM::factory('user', $message->response->from_user_id)
		));
		
		$this->set_title($message->response->title);
	}
	
	private function _send($send, $parent_id = 0)
	{
		$status = $send->response;
		$id = $parent_id > 0 ? $parent_id : $status;

		if ((int) $id > 0)
		{
			$this->go(Route::get('backend')->uri(array('controller' => 'messages', 'action' => 'view', 'id' => (int) $id)));
		}

		$this->go_back();
	}
}