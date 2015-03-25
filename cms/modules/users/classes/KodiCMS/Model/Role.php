<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Users
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Model_Role extends Model_Auth_Role {
	
	/**
	 * 
	 * @return array
	 */
	public function labels()
	{
		return array(
			'name' => __('Name'),
			'description' => __('Description')
		);
	}
	
	/**
	 * 
	 * @return array
	 */
	public function filters()
	{
		return array(
			'name' => array(
				array('strtolower'),
				array('URL::title')
			)
		);
	}
	
	/**
	 * 
	 * @return array
	 */
	public function form_columns()
	{
		return array(
			'id' => array(
				'type' => 'input',
				'editable' => FALSE,
				'length' => 10
			),
			'name' => array(
				'type' => 'input',
				'length' => 50
			),
			'description' => array(
				'type' => 'textarea'
			)
		);
	}

	/**
	 * Получение прав для роли
	 * 
	 * @return array
	 */
	public function permissions()
	{
		return DB::select('action')
			->from('roles_permissions')
			->where('role_id', '=', $this->id)
			->execute()
			->as_array(NULL, 'action');
	}
	
	/**
	 * Установка прав для роли
	 * 
	 * @param array $new_permissions
	 * @return \KodiCMS_Model_Role
	 */
	public function set_permissions( array $new_permissions = NULL )
	{
		DB::delete('roles_permissions')
			->where('role_id', '=', $this->id)
			->execute();

		if (!empty($new_permissions))
		{
			$insert = DB::insert('roles_permissions')
				->columns(array('role_id', 'action'));

			foreach ($new_permissions as $action => $status)
			{
				$insert->values(array($this->id, $action));
			}

			$insert->execute();
		}

		return $this;
	}
	
	/**************************************************************************
	 * Events
	 **************************************************************************/
	public function after_create()
	{	
		Kohana::$log->add(Log::INFO, 'Role :role has been added by :user', array(
			':role' => HTML::anchor(Route::get('backend')->uri(array(
				'controller' => 'roles',
				'action' => 'edit',
				'id' => $this->id
			)), $this->name),
		))->write();

		Observer::notify('role_after_add', $this);
	}
	
	public function after_update()
	{
		Kohana::$log->add(Log::INFO, 'Role :role has been updated by :user', array(
			':role' => HTML::anchor(Route::get('backend')->uri(array(
				'controller' => 'roles',
				'action' => 'edit',
				'id' => $this->id
			)), $this->name),
		))->write();

		Observer::notify('role_after_edit', $this);
	}
	
	public function before_delete()
	{
		Kohana::$log->add(Log::INFO, 'Role :role has been deleted by :user', array(
			':role' => HTML::anchor(Route::get('backend')->uri(array(
				'controller' => 'roles',
				'action' => 'edit',
				'id' => $this->id
			)), $this->name),
		))->write();

		Observer::notify('role_delete', $this->id);

		return TRUE;
	}
	
	public function after_delete($id)
	{
		DB::delete('roles_permissions')
			->where('role_id', '=', $id)
			->execute();
	}
}