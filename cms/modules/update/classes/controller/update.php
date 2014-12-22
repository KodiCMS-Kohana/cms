<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Update
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Update extends Controller_System_Backend {
	
	public function before()
	{
		parent::before();

		$this->breadcrumbs
			->add(__('Update'), Route::get('backend')->uri(array('controller' => 'update')));
	}
	
	public function action_index() 
	{
		$this->set_title(__('Update'));
		$this->template->content = View::factory( 'update/index');
	}
	
	public function action_database() 
	{
		Assets::package('ace');
		
		$this->set_title(__('Database'));
		
		$this->template->content = View::factory( 'update/database', array(
			'actions' => Update::check_database(FALSE),
		));
	}
	
	public function action_patches()
	{
		if($this->request->method() === Request::POST)
		{
			return $this->_apply_patch();
		}
		
		$this->set_title(__('Patches'));

		$this->template->content = View::factory( 'update/patches', array(
			'patches' => array_flip(Patch::find_all()),
		));
	}
	
	private function _apply_patch()
	{
		$patch = $this->request->post('patch');
		
		try
		{
			Patch::apply($patch);
		} 
		catch (Validation_Exception $ex)
		{
			Messages::errors($ex->errors());
		}
		catch (Kohana_Exception $ex) 
		{
			Messages::errors($ex->getMessage());
		}
		
		$this->go_back();
	}
}